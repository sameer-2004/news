<?php

namespace Modules\Api\Http\Controllers;
use Illuminate\Http\Request;
use Aws\S3\Exception\S3Exception as S3;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Modules\Api\Entities\AppIntro;
use Modules\Language\Entities\Language;
use Validator;
use Image;
use File;

class ApiSettingsController extends Controller
{
	public function index()
	{
		return view('api::api-settings');
	}

	public function androidSettings()
	{
		return view('api::android-settings');
	}

	public function iosSettings()
	{
		return view('api::ios-settings');
	}

	public function adsConfig()
	{
		return view('api::ads_config');
	}

	public function appIntro ()
	{
	    $app_intros = AppIntro::orderByDesc('id')->paginate(10);
        $activeLang = Language::where('status', 'active')->orderBy('name', 'ASC')->get();
		return view('api::intro', compact('activeLang','app_intros'));
	}

	public function addIntro (Request $request)
	{
        if (strtolower(\Config::get('app.demo_mode')) == 'yes'):
            return redirect()->back()->with('error', 'You are not allowed to add/modify in demo mode.');
        endif;
        Validator::make($request->all(), [
            'title' => 'required|min:2',
            'description' => 'required|min:2',
            'cover_image' => 'required|mimes:jpg,JPG,JPEG,jpeg,png|max:5120',
        ])->validate();

        $originalImageUrl     = "";


        if ($request->hasFile('cover_image')):

            $requestImage = $request->file('cover_image');

            $fileType = $requestImage->getClientOriginalExtension();

            $originalImageName = date('YmdHis') . "_Image_" . rand(1, 50) . '.' . $fileType;

            if (strpos(php_sapi_name(), 'cli') !== false || settingHelper('default_storage') == 's3' || defined('LARAVEL_START_FROM_PUBLIC')) :
                $directory = 'images/';
            else:
                $directory = 'public/images/';
            endif;

            $originalImageUrl       = $directory . $originalImageName;

            if (settingHelper('default_storage') == 's3'):

                //ogImage
                $imgOriginal    = Image::make($requestImage)->fit(236, 236)->stream();

                try {
                    Storage::disk('s3')->put($originalImageUrl, $imgOriginal);

                } catch (S3 $e) {
                    return redirect()->back()->with('error', __('something_went_wrong'));
                }
            elseif (settingHelper('default_storage') == 'local'):

                //                dd($requestImage);
                Image::make($requestImage)->fit(236, 236)->save($originalImageUrl);
            endif;

        endif;

        $appIntro = new AppIntro();

        $appIntro->title        = $request->title;
        $appIntro->language    = $request->language;


        $appIntro->image   = str_replace("public/", "", $originalImageUrl);
        $appIntro->disk = settingHelper('default_storage');

        $appIntro->description = $request->description;

        $appIntro->save();

        return redirect()->back()->with('success', __('successfully_added'));
	}

	public function editIntro($id)
	{
        $appIntro   = AppIntro::findOrfail($id);
        $activeLang = Language::where('status', 'active')->orderBy('name', 'ASC')->get();

        return view('api::edit_intro', compact('appIntro', 'activeLang'));
	}

	public function updateIntro(Request $request)
	{
        if (strtolower(\Config::get('app.demo_mode')) == 'yes'):
            return redirect()->back()->with('error', 'You are not allowed to add/modify in demo mode.');
        endif;
        Validator::make($request->all(), [
            'title' => 'required|min:2',
            'intro_id' => 'required',
            'description' => 'required|min:2',
            'image'     => 'mimes:jpg,JPG,JPEG,jpeg,png|max:5120',
        ])->validate();

        $requestImage = $request->file('image');

        $introImage = AppIntro::findOrfail($request->intro_id);

        if(isset($requestImage)):

            if($introImage->disk =='s3') :
                if (Storage::disk('s3')->exists($introImage->image) && !blank($introImage->image)) :
                    Storage::disk('s3')->delete($introImage->image);
                endif;
            elseif($introImage->disk =='local'):
                if (strpos(php_sapi_name(), 'cli') !== false || defined('LARAVEL_START_FROM_PUBLIC')) {
                    $path = '';
                }else{
                    $path = 'public/';
                }

                if (File::exists($path.$introImage->image) && !blank($introImage->image)) :
                    unlink($path.$introImage->image);
                endif;
            endif;

            $fileType = $requestImage->getClientOriginalExtension();

            $originalImageName = date('YmdHis') . "_image_" . rand(1, 50) . '.' . $fileType;

            if (strpos(php_sapi_name(), 'cli') !== false || settingHelper('default_storage') == 's3' || defined('LARAVEL_START_FROM_PUBLIC')) :
                $directory = 'images/';
            else:
                $directory = 'public/images/';
            endif;

            $originalImageUrl = $directory . $originalImageName;

            if (settingHelper('default_storage') == 's3'):

                $imgOriginal = Image::make($requestImage)->fit(236, 236)->stream();

                try {
                    Storage::disk('s3')->put($originalImageUrl, $imgOriginal);

                } catch (S3 $e) {
                    return redirect()->back()->with('error', __('something_went_wrong'));
                }

            elseif(settingHelper('default_storage') == 'local'):

                Image::make($requestImage)->fit(236, 236)->save($originalImageUrl);

            endif;

            $introImage->image   = str_replace("public/", "", $originalImageUrl);

            $introImage->disk             = settingHelper('default_storage');
        endif;

        $introImage->title       = $request->title;
        $introImage->language    = $request->language;
        $introImage->description = $request->description;

        $introImage->save();

        return redirect()->back()->with('success', __('successfully_updated'));
	}

	public function appConfig()
	{
		return view('api::app_config');
	}
}
