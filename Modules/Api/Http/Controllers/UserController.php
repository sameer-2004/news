<?php

namespace Modules\Api\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Modules\User\Entities\User;
use Cartalyst\Sentinel\Checkpoints\ThrottlingException;
use Cartalyst\Sentinel\Checkpoints\NotActivatedException;
use Activation;
use Sentinel;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use File;
use Image;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use App\Traits\ApiReturnFormat;
use Reminder;

class UserController extends Controller
{
    use ApiReturnFormat;

    public function __construct()
    {
        // $this->middleware('auth:api', ['except' => ['login','authenticate']]);
    }

    public function authenticate(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'email' => 'required|max:255',
                'password' => 'required|min:5|max:30',
            ]);

            if ($validator->fails()) {
                return $this->responseWithError(__('required_field_missing'), $validator->errors(), 422);
                // return response()->json($validator->errors(), 422);
            }

            $user = User::where('email', $request->email)->first();

            if ($user && $user->is_deleted == 1) {
                return $this->responseWithError(__('user_not_found'), [], 404);
            }

            if (blank($user)) {
                return $this->responseWithError( __('user_not_found'), [], 422);
            } elseif($user->is_user_banned == 0) {
                return $this->responseWithError( __('your_account_is_banned'), [], 401);
            }

            $credentials = $request->only('email', 'password');

            try {
                if (!$token = JWTAuth::attempt($credentials)) {
                    return $this->responseWithError(__('invalid_credentials'), [], 401);
                }
            } catch (JWTException $e) {
                return $this->responseWithError(__('could_not_create_token'), [] , 422);

            } catch (ThrottlingException $e) {
                return $this->responseWithError(__('suspicious_activity_on_your_ip'). $e->getDelay() .' '.  __('seconds'), [], 500);

            } catch (NotActivatedException $e) {
                return $this->responseWithError(__('you_account_not_activated_check_mail_or_contact_support'),[],400);

            } catch (Exception $e) {
                return $this->responseWithError(__('something_went_wrong'), [], 500);
            }

            Sentinel::authenticate($request->all());

            $user = User::where('email', $request->email)->first();

            $data['token'] = $token;
            $data['id'] = $user->id;

            $data['first_name'] = $user->first_name;
            $data['last_name'] = $user->last_name;

            if (isset($user->profile_image)):
                $data['image'] = static_asset($user->profile_image);
            else:
                $data['image'] = '';
            endif;
            $data['password_available'] = True;
            $data['join_date'] = Carbon::createFromFormat('Y-m-d H:i:s', $user['created_at'])->format('Y-m-d');
            $data['last_login'] = $user->last_login;
            $data['about'] = $user->about_us ?? ' ' ;
            $data['socials'] = $user->social_media;
            $data['gender'] = 'Other';

            if($user->gender == 1):
                $data['gender'] = 'Male';
            elseif ($user->gender == 2):
                $data['gender'] = 'Female';
            endif;

            $data['phone'] = $user->phone;
            $data['email'] = $user->email;
            $data['dob'] = $user->dob;

            return $this->responseWithSuccess(__('successfully_login'), $data, 200);
        } catch (\Exception $e) {
            return $this->responseWithError(__('something_went_wrong_please_try_again'), [], 500);
        }
    }

    public function register(Request $request)
    {
        try{
            // return $request;
            // return Config::get('app.locale');
            $validator = Validator::make($request->all(), [
                'first_name' => 'required',
                'last_name' => 'required|min:2|max:30',
                'email' => 'required|unique:users|max:255',
                'gender' => 'required',
                'password' => 'confirmed|required|min:6|max:30',
                'password_confirmation' => 'required|min:6|max:30'
            ]);

            $request['phone'] = $request->phone ?? '00'.rand('100000000','999999999');

            if ($validator->fails()) {
                // return $validator->getMessageBag()->all();

                return $this->responseWithError(__('required_field_missing'), $validator->errors(), 422);
            }
            $request['user_role'] = 'user';

            $user = Sentinel::register($request->all());
            $role = Sentinel::findRoleBySlug($request->user_role);
            $role->users()->attach($user);
            $activation = Activation::create($user);

            try{
                sendMail($user, $activation->code, 'activate_account', $request->password);
            }
            catch (\Exception $e) {
                return $this->responseWithError(__('test_mail_error_message'), [], 550);
            }


            $user['gender'] = 'Other';

            if($request->gender == 1):
                $user['gender'] = 'Male';
            elseif ($request->gender == 2):
                $user['gender'] = 'Female';
            endif;

            return $this->responseWithSuccess(__('check_user_mail_for_active_this_account'), $user, 200);
        } catch (\Exception $e) {
            return $this->responseWithError(__('something_went_wrong_please_try_again'), [], 500);
        }

    }

    public function getAuthenticatedUser()
    {
        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return $this->responseWithError('user_not_found', '' , 404);
            }

        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json(['token_expired'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['token_invalid'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        }

        $data['id'] = $user->id;

        $data['first_name'] = $user->first_name;
        $data['last_name'] = $user->last_name;

        if (isset($user->profile_image)):
            $data['image'] = static_asset($user->profile_image);
        else:
            $data['image'] = '';
        endif;
        $data['password_available'] = $user->is_password_set ? True: False;
        $data['join_date'] = Carbon::createFromFormat('Y-m-d H:i:s', $user['created_at'])->format('Y-m-d');
        $data['last_login'] = $user->last_login;
        $data['about'] = $user->about_us ?? ' ';
        $data['socials'] = $user->social_media;
        $data['gender'] = 'Other';

        if($user->gender == 1):
            $data['gender'] = 'Male';
        elseif ($user->gender == 2):
            $data['gender'] = 'Female';
        endif;
        $data['phone'] = $user->phone;
        $data['email'] = $user->email;
        $data['dob'] = $user->dob;

        return $this->responseWithSuccess(__('successfully_found'), $data, 200);
    }

    public function logout()
    {
        try {
            Sentinel::logout();
            JWTAuth::invalidate(JWTAuth::getToken());
            return $this->responseWithSuccess(__('successfully_logout'),[] ,200);
        } catch (JWTException $e) {
            JWTAuth::unsetToken();
            // something went wrong tries to validate a invalid token
            return $this->responseWithError(__('failed_to_logout'), [], 422);
        }
    }

    public function updateUserInfo(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'id'            => 'required',
                'first_name'    => 'required',
                'last_name'     => 'required|min:2|max:30',
                'image'         => 'mimes:jpg,JPG,JPEG,jpeg,png|max:5120',
                'email'         => 'required|unique:users,email,'.\Request()->id,
            ]);

            if ($validator->fails()) {
                return $this->responseWithError(__('required_field_missing'), $validator->errors(), 422);
            }
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return $this->responseWithError(__('unauthorized_user'), '' , 404);
            }

            $image = $request->file('image');
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

            $user->first_name   = $request->first_name;
            $user->last_name    = $request->last_name;
            $user->about_us     = $request->about;
            $user->email        = $request->email;
            $user->gender       = $request->gender ?? 0;
            $user->dob          = date('Y-m-d', strtotime($request->dob));
            $user->phone        = $request->phone ?? '00'.rand('100000000,999999999');

            $user->save();

            $data['id'] = $user->id;
            $data['token'] = JWTAuth::fromUser($user);

            $data['first_name'] = $user->first_name;
            $data['last_name'] = $user->last_name;

            if (isset($user->profile_image)):
                $data['image'] = static_asset($user->profile_image);
            else:
                $data['image'] = '';
            endif;
            $data['password_available'] = $user->is_password_set ? True: False;
            $data['join_date'] = Carbon::createFromFormat('Y-m-d H:i:s', $user['created_at'])->format('Y-m-d');
            $data['last_login'] = $user->last_login;
            $data['about'] = $user->about_us ?? ' ';
            $data['socials'] = $user->social_media;
            $data['gender'] = 'Other';

            if($user->gender == 1):
                $data['gender'] = 'Male';
            elseif ($user->gender == 2):
                $data['gender'] = 'Female';
            endif;
            $data['phone'] = $user->phone;
            $data['email'] = $user->email;
            $data['dob'] = $user->dob;

            return $this->responseWithSuccess(__('successfully_updated'), $data, 200);
        } catch (\Exception $e) {
            return $this->responseWithError(__('something_went_wrong_please_try_again'), [], 500);
        }

    }

    public function changePassword(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'old_password'  => 'required|different:password',
                'password'      => 'required|min:6|max:30',
                'password_confirmation'      => 'required|same:password|min:6|max:30',
            ]);

            if ($validator->fails()) {
                return $this->responseWithError(__('required_field_missing'), $validator->errors(), 422);
            }

            $hasher         = Sentinel::getHasher();

            $oldPassword    = $request->old_password;
            $password       = $request->password;
            $is_password_set= 1;

            $user           = Sentinel::getUser();

            if (!$hasher->check($oldPassword, $user->password)) {
                return $this->responseWithError(__('please_check_again'), '', 422);
            }

            Sentinel::update($user, array('password' => $password, 'is_password_set' =>$is_password_set));

            $data['id'] = $user->id;

            $data['first_name'] = $user->first_name;
            $data['last_name'] = $user->last_name;

            if (isset($user->profile_image)):
                $data['image'] = static_asset($user->profile_image);
            else:
                $data['image'] = '';
            endif;
            $data['password_available'] = $user->is_password_set ? True: False;
            $data['join_date'] = Carbon::createFromFormat('Y-m-d H:i:s', $user['created_at'])->format('Y-m-d');
            $data['last_login'] = $user->last_login;
            $data['about'] = $user->about_us ?? ' ';
            $data['socials'] = $user->social_media;
            $data['gender'] = 'Other';

            if($user->gender == 1):
                $data['gender'] = 'Male';
            elseif ($user->gender == 2):
                $data['gender'] = 'Female';
            endif;
            $data['phone'] = $user->phone;
            $data['email'] = $user->email;
            $data['dob'] = $user->dob;

            return $this->responseWithSuccess(__('successfully_updated'), $data, 200);
        } catch (\Exception $e) {
            return $this->responseWithError(__('something_went_wrong_please_try_again'), [], 500);
        }
    }

    public function setPassword(Request $request){
        try{
            $validator = Validator::make($request->all(), [
                'id'                => 'required',
                'firebase_auth_id' => 'required|min:4',
                'password'          => 'required|min:6|max:30',
            ]);

            if ($validator->fails()) {
                return $this->responseWithError(__('required_field_missing'), $validator->errors(), 422);
            }

            $is_valid_user = User::where('firebase_auth_id', $request->firebase_auth_id)->first();

            if ($is_valid_user):
                if($is_valid_user->is_password_set):
                    return $this->responseWithError(__('you_are_not_allowed_to_set_this_password'), __('invalid_attempt'),401);
                else:
                    $password       = $request->password;

                    Sentinel::update($is_valid_user, array('password' => $password));

                    $is_valid_user->is_password_set = 1;

                    $is_valid_user->save();

                    return $this->responseWithSuccess(__('successfully_updated'), '', 200);
                endif;
            else:
                return $this->responseWithError(__('user_not_found'), '' , 404);
            endif;
        } catch (\Exception $e) {
            return $this->responseWithError(__('something_went_wrong_please_try_again'), [], 500);
        }

    }

    public function userDetailsById(Request $request){
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required|integer',
            ]);

            if ($validator->fails()) {
                return $this->responseWithError(__('please_enter_valid_user_id'), $validator->errors(), 400);
            }

            try {
                if (!$user = JWTAuth::parseToken()->authenticate()) {
                    return $this->responseWithError('user_not_found', '' , 404);
                }

            } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
                return response()->json(['token_expired'], $e->getStatusCode());
            } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
                return response()->json(['token_invalid'], $e->getStatusCode());
            } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
                return response()->json(['token_absent'], $e->getStatusCode());
            }

            if ($user = User::find($request->id)):

                $data['id'] = $user->id;

                $data['first_name'] = $user->first_name;
                $data['last_name'] = $user->last_name;

                if (isset($user->profile_image)):
                    $data['image'] = static_asset($user->profile_image);
                else:
                    $data['image'] = '';
                endif;
                $data['password_available'] = $user->is_password_set ? True: false;
                $data['join_date'] = Carbon::createFromFormat('Y-m-d H:i:s', $user['created_at'])->format('Y-m-d');
                $data['last_login'] = $user->last_login;
                $data['about'] = $user->about_us ?? ' ';
                $data['socials'] = $user->social_media;
                $data['gender'] = 'Other';

                if($user->gender == 1):
                    $data['gender'] = 'Male';
                elseif ($user->gender == 2):
                    $data['gender'] = 'Female';
                endif;
                $data['phone'] = $user->phone;
                $data['email'] = $user->email;
                $data['dob'] = $user->dob;

                return $this->responseWithSuccess(__('successfully_found'), $data, 200);
            else:
                return $this->responseWithError(__('user_not_found'), '', 404);
            endif;
        }catch (\Exception $e) {
            return $this->responseWithError(__('something_went_wrong_please_try_again'), [], 500);
        }
    }
    public function userDetailsByEmail(Request $request){
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
            ]);

            if ($validator->fails()) {
                return $this->responseWithError(__('please_enter_valid_user_email'), $validator->errors());
            }

            try {
                if (!$user = JWTAuth::parseToken()->authenticate()) {
                    return $this->responseWithError(__('user_not_found'), '' , 404);
                }

            } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
                return response()->json(['token_expired'], $e->getStatusCode());
            } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
                return response()->json(['token_invalid'], $e->getStatusCode());
            } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
                return response()->json(['token_absent'], $e->getStatusCode());
            }

            if ($user = User::where('email', $request->email)->first()):

                $data['id'] = $user->id;

                $data['first_name'] = $user->first_name;
                $data['last_name'] = $user->last_name;

                if (isset($user->profile_image)):
                    $data['image'] = static_asset($user->profile_image);
                else:
                    $data['image'] = '';
                endif;
                $data['password_available'] = $user->is_password_set ? True: false;
                $data['join_date'] = Carbon::createFromFormat('Y-m-d H:i:s', $user['created_at'])->format('Y-m-d');
                $data['last_login'] = $user->last_login;
                $data['about'] = $user->about_us ?? ' ';
                $data['socials'] = $user->social_media;
                $data['gender'] = 'Other';

                if($user->gender == 1):
                    $data['gender'] = 'Male';
                elseif ($user->gender == 2):
                    $data['gender'] = 'Female';
                endif;
                $data['phone'] = $user->phone;
                $data['email'] = $user->email;
                $data['dob'] = $user->dob;

                return $this->responseWithSuccess(__('successfully_found'), $data, 200);
            else:
                return $this->responseWithError(__('user_not_found'), '', 404);
            endif;
        } catch (\Exception $e) {
            return $this->responseWithError(__('something_went_wrong_please_try_again'), [], 500);
        }

    }

    public function deactivateAccount(Request $request){
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required',
                'password' => 'required',
                'reason' => 'required|min:2',
            ]);

            if ($validator->fails()) {
                return $this->responseWithError(__('required_field_missing'), $validator->errors(), 422);
            }

            $user       = User::find($request->id);

            $user['gender'] = 'Other';

            if($user->gender == 1):
                $user['gender'] = 'Male';
            elseif ($user->gender == 2):
                $user['gender'] = 'Female';
            endif;

            $hasher     = Sentinel::getHasher();

            if (!$hasher->check($request->password, $user->password)) {
                return $this->responseWithError(__('password_wrong'), $user, 422);
            }

            $activation = \Modules\User\Entities\Activation::where('user_id',$user->id)->first();

            $activation->completed = 0;

            $activation->save();

            return $this->responseWithSuccess(__('successfully_updated'), 200);
        } catch (\Exception $e) {
            return $this->responseWithError(__('something_went_wrong_please_try_again'), [], 500);
        }

    }

    public function deleteAccount(Request $request)
    {
        DB::beginTransaction();
        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return $this->responseWithError(__('user_not_found'), '' , 404);
            }
            if (strtolower(\Config::get('app.demo_mode')) == 'yes'):
                return $this->responseWithError(__('You are not allowed to add/modify in demo mode.'), [], 404);
            endif;
            $user->is_deleted = 1;
            $user->save();

            $activation = \Modules\User\Entities\Activation::where('user_id',$user->id)->first();

            $activation->completed = 0;

            $activation->save();

            Sentinel::logout();
            JWTAuth::invalidate(JWTAuth::getToken());

            DB::commit();
            return $this->responseWithSuccess(__('account_deleted'), 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->responseWithError(__('something_went_wrong_please_try_again'), [], 500);
        }

    }

    public function firebaseAuth(Request $request){
        try{
            $validator = Validator::make($request->all(), [
                'uid'   => 'required',
            ]);

            if ($validator->fails()) {
                return $this->responseWithError(__('required_field_missing'), $validator->errors(),422);
            }

            if($user = User::where('firebase_auth_id', $request->uid)->first()):
                if($user->is_user_banned == 0) :
                    return $this->responseWithError( __('your_account_is_banned'), [], 401);
                elseif($user->withActivation->completed == 0) :
                    return $this->responseWithError( __('you_account_not_activated_check_mail_or_contact_support'), [], 401);
                endif;

                try {
                    if (!$token = JWTAuth::fromUser($user)) {
                        return $this->responseWithError(__('invalid_credentials'), [], 401);
                    }
                } catch (JWTException $e) {
                    return $this->responseWithError(__('could_not_create_token'), [] , 422);

                } catch (ThrottlingException $e) {
                    return $this->responseWithError(__('suspicious_activity_on_your_ip'). $e->getDelay() .' '.  __('seconds'), [], 500);

                } catch (NotActivatedException $e) {
                    return $this->responseWithError(__('you_account_not_activated_check_mail_or_contact_support'),[],400);

                } catch (Exception $e) {
                    return $this->responseWithError(__('something_went_wrong'), [], 500);
                }

                $request['password'] = 'social-login';

                Sentinel::authenticate($request->all());

                $data['id'] = $user->id;
                $data['token'] = $token;

                $data['first_name'] = $user->first_name;
                $data['last_name'] = $user->last_name;

                if (isset($user->profile_image)):
                    $data['image'] = static_asset($user->profile_image);
                else:
                    $data['image'] = '';
                endif;
                $data['password_available'] = $user->is_password_set ? True: false;
                $data['join_date'] = Carbon::createFromFormat('Y-m-d H:i:s', $user['created_at'])->format('Y-m-d');
                $data['last_login'] = $user->last_login;
                $data['about'] = $user->about_us ?? ' ';
                $data['socials'] = $user->social_media;
                $data['gender'] = 'Other';

                if($user->gender == 1):
                    $data['gender'] = 'Male';
                elseif ($user->gender == 2):
                    $data['gender'] = 'Female';
                endif;
                $data['phone'] = $user->phone;
                $data['email'] = $user->email;
                $data['dob'] = $user->dob;

                return $this->responseWithSuccess(__('successfully_login'), $data, 200);

            elseif($user = User::where('email', $request->email)->first()):
                if($user->is_user_banned == 0) :
                    return $this->responseWithError( __('your_account_is_banned'), [], 401);
                elseif($user->withActivation->completed == 0) :
                    return $this->responseWithError( __('you_account_not_activated_check_mail_or_contact_support'), [], 401);
                endif;

                try {
                    if (!$token = JWTAuth::fromUser($user)) {
                        return $this->responseWithError(__('invalid_credentials'), [], 401);
                    }
                } catch (JWTException $e) {
                    return $this->responseWithError(__('could_not_create_token'), [] , 422);

                } catch (ThrottlingException $e) {
                    return $this->responseWithError(__('suspicious_activity_on_your_ip'). $e->getDelay() .' '.  __('seconds'), [], 500);

                } catch (NotActivatedException $e) {
                    return $this->responseWithError(__('you_account_not_activated_check_mail_or_contact_support'),[],400);

                } catch (Exception $e) {
                    return $this->responseWithError(__('something_went_wrong'), [], 500);
                }

//                $request['password'] = 'social-login';

                Sentinel::authenticate($request->all());

                $data['id'] = $user->id;
                $data['token'] = $token;

                $data['first_name'] = $user->first_name;
                $data['last_name'] = $user->last_name;

                if (isset($user->profile_image)):
                    $data['image'] = static_asset($user->profile_image);
                else:
                    $data['image'] = '';
                endif;
                $data['password_available'] = $user->is_password_set ? True: false;
                $data['join_date'] = Carbon::createFromFormat('Y-m-d H:i:s', $user['created_at'])->format('Y-m-d');
                $data['last_login'] = $user->last_login;
                $data['about'] = $user->about_us ?? ' ';
                $data['socials'] = $user->social_media;
                $data['gender'] = 'Other';

                if($user->gender == 1):
                    $data['gender'] = 'Male';
                elseif ($user->gender == 2):
                    $data['gender'] = 'Female';
                endif;
                $data['phone'] = $user->phone;
                $data['email'] = $user->email;
                $data['dob'] = $user->dob;

                return $this->responseWithSuccess(__('successfully_login'), $data, 200);

            elseif($user = User::where('phone', $request->phone)->first()):
                if($user->is_user_banned == 0) :
                    return $this->responseWithError( __('your_account_is_banned'), [], 401);
                elseif($user->withActivation->completed == 0) :
                    return $this->responseWithError( __('you_account_not_activated_check_mail_or_contact_support'), [], 401);
                endif;

                try {
                    if (!$token = JWTAuth::fromUser($user)) {
                        return $this->responseWithError(__('invalid_credentials'), [], 401);
                    }
                } catch (JWTException $e) {
                    return $this->responseWithError(__('could_not_create_token'), [] , 422);

                } catch (ThrottlingException $e) {
                    return $this->responseWithError(__('suspicious_activity_on_your_ip'). $e->getDelay() .' '.  __('seconds'), [], 500);

                } catch (NotActivatedException $e) {
                    return $this->responseWithError(__('you_account_not_activated_check_mail_or_contact_support'),[],400);

                } catch (Exception $e) {
                    return $this->responseWithError(__('something_went_wrong'), [], 500);
                }

//                $request['password'] = 'social-login';

                Sentinel::authenticate($request->all());

                $data['id'] = $user->id;
                $data['token'] = $token;

                $data['first_name'] = $user->first_name;
                $data['last_name'] = $user->last_name;

                if (isset($user->profile_image)):
                    $data['image'] = static_asset($user->profile_image);
                else:
                    $data['image'] = '';
                endif;
                $data['password_available'] = $user->is_password_set ? True: false;
                $data['join_date'] = Carbon::createFromFormat('Y-m-d H:i:s', $user['created_at'])->format('Y-m-d');
                $data['last_login'] = $user->last_login;
                $data['about'] = $user->about_us ?? ' ';
                $data['socials'] = $user->social_media;
                $data['gender'] = 'Other';

                if($user->gender == 1):
                    $data['gender'] = 'Male';
                elseif ($user->gender == 2):
                    $data['gender'] = 'Female';
                endif;
                $data['phone'] = $user->phone;
                $data['email'] = $user->email;
                $data['dob'] = $user->dob;

                return $this->responseWithSuccess(__('successfully_login'), $data, 200);
            else:
                $request['user_role'] = 'user';
                $request['password'] = 'social-login';

                if(!isset($request['email'])):
                    $request['email'] = uniqid().'@mail.com';
                endif;

                if(!isset($request['phone'])):
                    $request['phone'] = '00000000000';
                endif;

                $user = Sentinel::register($request->all());
                $user->firebase_auth_id = $request->uid;
                $role = Sentinel::findRoleBySlug($request->user_role);
                $role->users()->attach($user);
                $activation = Activation::create($user);
                Activation::complete($user, $activation->code);

                $user->save();

                $data['id'] = $user->id;


                try {
                    if (!$token = JWTAuth::fromUser($user)) {
                        return $this->responseWithError(__('invalid_credentials'), [], 401);
                    }
                } catch (JWTException $e) {
                    return $this->responseWithError(__('could_not_create_token'), [] , 422);

                } catch (ThrottlingException $e) {
                    return $this->responseWithError(__('suspicious_activity_on_your_ip'). $e->getDelay() .' '.  __('seconds'), [], 500);

                } catch (NotActivatedException $e) {
                    return $this->responseWithError(__('you_account_not_activated_check_mail_or_contact_support'),[],400);

                } catch (Exception $e) {
                    return $this->responseWithError(__('something_went_wrong'), [], 500);
                }

//                $request['password'] = 'social-login';

                Sentinel::authenticate($request->all());

                $data['first_name'] = $user->first_name;
                $data['last_name'] = $user->last_name;
                $data['token'] = $token;

                if (isset($user->profile_image)):
                    $data['image'] = static_asset($user->profile_image);
                else:
                    $data['image'] = '';
                endif;
                $data['password_available'] = $user->is_password_set ? True: false;
                $data['join_date'] = Carbon::createFromFormat('Y-m-d H:i:s', $user['created_at'])->format('Y-m-d');
                $data['last_login'] = $user->last_login;
                $data['about'] = $user->about_us ?? ' ';
                $data['socials'] = $user->social_media;
                $data['gender'] = 'Other';

                if($user->gender == 1):
                    $data['gender'] = 'Male';
                elseif ($user->gender == 2):
                    $data['gender'] = 'Female';
                endif;
                $data['phone'] = $user->phone;
                $data['email'] = $user->email;
                $data['dob'] = $user->dob;

                return $this->responseWithSuccess(__('successfully_login'), $data, 200);
            endif;

            return $this->responseWithError(__('something_went_wrong'), '', 500);
        } catch (\Exception $e) {
            dd($e);
            return $this->responseWithError(__('something_went_wrong_please_try_again'), [], 500);
        }

    }

    public function forgotPassword(Request $request)
    {
        try{

            $validator = Validator::make($request->all(), [
                'email'   => 'required',
            ]);

            if ($validator->fails()) {
                return $this->responseWithError(__('required_field_missing'), $validator->errors(),422);
            }
            $user       = User::whereEmail($request->email)->first();

            if (blank($user)):
                return $this->responseWithError(__('user_not_found'), [], 500);
            endif;

            if (Reminder::exists($user)) :
                $remainder = Reminder::where('user_id', $user->id)->first();
            else :
                $remainder = Reminder::create($user);
            endif;
            //send a mail to user
            sendMail($user, $remainder->code, 'forgot_password', '');

            return $this->responseWithSuccess(__('reset_code_is_send_to_mail'), [], 200);

        } catch (\Exception $e) {
            return $this->responseWithError(__('test_mail_error_message'), [], 500);
        }
    }


    public function test(Request $request)
    {
        return Config::get('app.locale');
    }

}
