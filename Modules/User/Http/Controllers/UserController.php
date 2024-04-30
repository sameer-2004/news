<?php

namespace Modules\User\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\User\Entities\Role;
use Modules\User\Entities\User;
use Cartalyst\Sentinel\Checkpoints\ThrottlingException;
use Cartalyst\Sentinel\Checkpoints\NotActivatedException;
use Validator;
use Activation;
use Sentinel;
use Illuminate\Support\Facades\Mail;
use DB;
use File;
use Image;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('user::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $roles  = Role::allRole();
        return view('user::add-user', compact('roles'));
    }

    /**
     * user add
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        if (strtolower(\Config::get('app.demo_mode')) == 'yes'):
            return redirect()->back()->with('error', __('You are not allowed to add/modify in demo mode.'));
        endif;
        Validator::make($request->all(), [
            'first_name'            => 'required',
            'email'                 => 'required|unique:users|max:255',
            'last_name'             => 'required|min:2|max:30',
            'user_role'             => 'required|min:2|max:30',
            'password'              => 'confirmed|required|min:5|max:30',
            'password_confirmation' => 'required|min:5|max:30',
            'profile_image'         => 'mimes:jpg,JPG,JPEG,jpeg,gif,png,ico|max:5120',
        ])->validate();

        $request['is_password_set'] = 1;

        try {
            $user       = Sentinel::register($request->all());

            $image = $request->file('profile_image');

            if(isset($image)):
//            make unique name for image
                $currentDate = Carbon::now()->toDateString();
                $imageName  = $request->first_name.'-'.$currentDate.'-'.uniqid().'.'.$image->getClientOriginalExtension();

                if (strpos(php_sapi_name(), 'cli') !== false  || defined('LARAVEL_START_FROM_PUBLIC')) :

                    $directory              = 'images/';
                else:
                    $directory              = 'public/images/';
                endif;

                $profileImgUrl             = $directory . $imageName;

                if (strpos(php_sapi_name(), 'cli') !== false || defined('LARAVEL_START_FROM_PUBLIC')) {
                    $path = '';
                }else{
                    $path = 'public/';
                }

                Image::make($image)->fit(260, 200)->save($profileImgUrl);

                $user->profile_image    = str_replace("public/","",$profileImgUrl);

                $user->save();

            endif;

            $role       = Sentinel::findRoleBySlug($request->user_role);

            $role->users()->attach($user);
            $activation = Activation::create($user);

            sendMail($user, $activation->code, 'activate_account', $request->password);

            return \redirect()->back()->with('success', __('check_user_mail_for_active_this_account'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', __('test_mail_error_message'));
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    //view user list
    public function userList()
    {

        $roles      = Role::allRole();
        $users      = User::with(['withRoles', 'withActivation', 'image'])->paginate(10);

        return view('user::users', compact('users', 'roles'));
    }

    //update user info
    public function updateUserInfo(Request $request, $id)
    {
        if (strtolower(\Config::get('app.demo_mode')) == 'yes'):
            return redirect()->back()->with('error', __('You are not allowed to add/modify in demo mode.'));
        endif;
        Validator::make($request->all(), [
            'first_name'    => 'required',
            'last_name'     => 'required|min:2|max:30',
            'profile_image' => 'mimes:jpg,JPG,JPEG,jpeg,gif,png,ico|max:5120',
            'phone'         => ['min:11','max:14'],
            'dob'           => 'required',
            'gender'        => 'required',
        ])->validate();

        $user                       = User::find($id);

        $image = $request->file('profile_image');

        if(isset($image)):
//            make unique name for image
            $currentDate = Carbon::now()->toDateString();
            $imageName  = $request->first_name.'-'.$currentDate.'-'.uniqid().'.'.$image->getClientOriginalExtension();

            if (strpos(php_sapi_name(), 'cli') !== false  || defined('LARAVEL_START_FROM_PUBLIC')) :

                $directory              = 'images/';
            else:
                $directory              = 'public/images/';
            endif;

            $profileImgUrl             = $directory . $imageName;

            if (strpos(php_sapi_name(), 'cli') !== false || defined('LARAVEL_START_FROM_PUBLIC')) {
                $path = '';
            }else{
                $path = 'public/';
            }

            if (File::exists($path.$user->profile_image) && !blank($user->profile_image)) :
                unlink($path.$user->profile_image);
            endif;

            Image::make($image)->fit(260, 200)->save($profileImgUrl);

            $user->profile_image    = str_replace("public/","",$profileImgUrl);

            $user->save();

        endif;

        $user->first_name           = $request->first_name;
        $user->last_name            = $request->last_name;
        $user->phone                = $request->phone;
        $user->dob                  = $request->dob;
        $user->gender               = $request->gender;

        $user->newsletter_enable    = $request->newsletter_enable;

        $user->save();

        return redirect()->back()->with('success', __('successfully_updated'));
    }

    public function myProfile()
    {
        return view('user::user-profile');
    }

    public function changePasswordByMe(Request $request)
    {
        Validator::make($request->all(), [
            'old_password'  => 'required|different:password',
            'password'      => 'required|min:6|max:30',
            'password_confirmation'      => 'required|same:password|min:6|max:30'
        ])->validate();

        $hasher         = Sentinel::getHasher();

        $oldPassword    = $request->old_password;
        $password       = $request->password;

        $user           = Sentinel::getUser();

        if (!$hasher->check($oldPassword, $user->password)) {
            return redirect()->back()->with('error', __('please_check_again'));
        }

        Sentinel::update($user, array('password' => $password));

        return redirect()->back()->with('success', __('successfully_updated'));
    }
}
