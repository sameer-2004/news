<?php

namespace Modules\Gallery\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Gallery\Entities\Audio;
use Modules\Gallery\Entities\Image as galleryImage;
use Modules\Gallery\Entities\Video;
use DB;
use File;
use Image;
use Validator;
use Illuminate\Support\Facades\Storage;
use Aws\S3\Exception\S3Exception as S3;
use Modules\Common\Entities\Cron;

class GalleryController extends Controller
{
    public function index()
    {
        return view('gallery::index');
    }

    public function imageGallery(){

        try {
            $url        = 'https://s3.' . config('filesystems.disks.s3.region') . '.amazonaws.com/' . config('filesystems.disks.s3.bucket') . '/';
            $images     = [];
            $files      = Storage::disk('s3')->files('images');

            foreach ($files as $file) {
                $images[] = [
                    'name' => str_replace('images/', '', $file),
                    'src' => $url . $file
                ];
            }

            return $images;
        } catch(S3 $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        return view('gallery::media_gallery');
    }

    public function imageUpload(Request $request){
        if ($request->file('image')) :
            $validation = Validator::make($request->all(), [
                'image' => 'required|mimes:jpg,JPG,JPEG,jpeg,png|max:5120',
            ])->validate();

            $image                  = new galleryImage();

            $requestImage           = $request->file('image');
            $fileType               = $requestImage->getClientOriginalExtension();

            $originalImageName      = date('YmdHis') . "_original_" . rand(1, 50) . '.' . 'webp';
            $ogImageName = date('YmdHis') . "_ogImage_" . rand(1, 50) . '.' . $fileType;
            $thumbnailImageName     = date('YmdHis') . "_thumbnail_100x100_" . rand(1, 50) . '.' . 'webp';
            $bigImageName           = date('YmdHis') . "_big_1080x1000_" . rand(1, 50) . '.' . 'webp';
            $bigImageNameTwo        = date('YmdHis') . "_big_730x400_" . rand(1, 50) . '.' . 'webp';
            $mediumImageName        = date('YmdHis') . "_medium_358x215_" . rand(1, 50) . '.' . 'webp';
            $mediumImageNameTwo     = date('YmdHis') . "_medium_350x190_" . rand(1, 50) . '.' . 'webp';
            $mediumImageNameThree   = date('YmdHis') . "_medium_255x175_" . rand(1, 50) . '.' . 'webp';
            $smallImageName         = date('YmdHis') . "_small_123x83_" . rand(1, 50) . '.' . 'webp';

            // image upload directory
            if (strpos(php_sapi_name(), 'cli') !== false || settingHelper('default_storage') =='s3' || defined('LARAVEL_START_FROM_PUBLIC')) :
                $directory              = 'images/';
            else:
                $directory              = 'public/images/';
            endif;

            $originalImageUrl       = $directory . $originalImageName;
            $ogImageUrl             = $directory . $ogImageName;
            $thumbnailImageUrl      = $directory . $thumbnailImageName;
            $bigImageUrl            = $directory . $bigImageName;
            $bigImageUrlTwo         = $directory . $bigImageNameTwo;
            $mediumImageUrl         = $directory . $mediumImageName;
            $mediumImageUrlTwo      = $directory . $mediumImageNameTwo;
            $mediumImageUrlThree    = $directory . $mediumImageNameThree;
            $smallImageUrl          = $directory . $smallImageName;

            if(settingHelper('default_storage') =='s3') :
                //ogImage
                $imgOg = Image::make($requestImage)->fit(730, 400)->stream();

                //jpg. jpeg, JPEG, JPG compression
                if ($fileType == 'jpeg' or $fileType == 'jpg' or $fileType == 'JPEG' or $fileType == 'JPG'):
                    $imgOriginal    = Image::make(imagecreatefromjpeg($requestImage))->encode('webp', 80);
                    $imgThumbnail   = Image::make(imagecreatefromjpeg($requestImage))->fit(100, 100)->encode('webp', 80);
                    $imgBig         = Image::make(imagecreatefromjpeg($requestImage))->fit(1080, 1000)->encode('webp', 80);
                    $imgBigTwo      = Image::make(imagecreatefromjpeg($requestImage))->fit(730, 400)->encode('webp', 80);
                    $imgMedium      = Image::make(imagecreatefromjpeg($requestImage))->fit(358, 215)->encode('webp', 80);
                    $imgMediumTwo   = Image::make(imagecreatefromjpeg($requestImage))->fit(350, 190)->encode('webp', 80);
                    $imgMediumThree = Image::make(imagecreatefromjpeg($requestImage))->fit(255, 175)->encode('webp', 80);
                    $imgSmall       = Image::make(imagecreatefromjpeg($requestImage))->fit(123, 83)->encode('webp', 80);

                //png compression
                elseif ($fileType == 'PNG' or $fileType == 'png'):

                    $imgOriginal    = Image::make(imagecreatefrompng($requestImage))->encode('webp', 80);
                    $imgThumbnail   = Image::make(imagecreatefrompng($requestImage))->fit(100, 100)->encode('webp', 80);
                    $imgBig         = Image::make(imagecreatefrompng($requestImage))->fit(1080, 1000)->encode('webp', 80);
                    $imgBigTwo      = Image::make(imagecreatefrompng($requestImage))->fit(730, 400)->encode('webp', 80);
                    $imgMedium      = Image::make(imagecreatefrompng($requestImage))->fit(358, 215)->encode('webp', 80);
                    $imgMediumTwo   = Image::make(imagecreatefrompng($requestImage))->fit(350, 190)->encode('webp', 80);
                    $imgMediumThree = Image::make(imagecreatefrompng($requestImage))->fit(255, 175)->encode('webp', 80);
                    $imgSmall       = Image::make(imagecreatefrompng($requestImage))->fit(123, 83)->encode('webp', 80);

                endif;

                try {
                    Storage::disk('s3')->put($originalImageUrl, $imgOriginal);
                    Storage::disk('s3')->put($ogImageUrl, $imgOg);
                    Storage::disk('s3')->put($thumbnailImageUrl, $imgThumbnail);
                    Storage::disk('s3')->put($bigImageUrl, $imgBig);
                    Storage::disk('s3')->put($bigImageUrlTwo, $imgBigTwo);
                    Storage::disk('s3')->put($mediumImageUrl, $imgMedium);
                    Storage::disk('s3')->put($mediumImageUrlTwo, $imgMediumTwo);
                    Storage::disk('s3')->put($mediumImageUrlThree, $imgMediumThree);
                    Storage::disk('s3')->put($smallImageUrl, $imgSmall);

                } catch(S3 $e) {
                    $data['status']     = 'error';
                    $data['message']    = $e->getMessage();

                    return Response()->json($data);
                }

            elseif(settingHelper('default_storage') =='local'):
                Image::make($requestImage)->save($ogImageUrl);

                //jpg. jpeg, JPEG, JPG compression
                if ($fileType == 'jpeg' or $fileType == 'jpg' or $fileType == 'JPEG' or $fileType == 'JPG'):
                    Image::make(imagecreatefromjpeg($requestImage))->save($originalImageUrl, 80);

                    Image::make(imagecreatefromjpeg($requestImage))->fit(150, 150)->save($thumbnailImageUrl, 80);
                    Image::make(imagecreatefromjpeg($requestImage))->resize(1080, 1000)->save($bigImageUrl, 80);
                    Image::make(imagecreatefromjpeg($requestImage))->resize(730, 400)->save($bigImageUrlTwo, 80);
                    Image::make(imagecreatefromjpeg($requestImage))->resize(358, 215)->save($mediumImageUrl, 80);
                    Image::make(imagecreatefromjpeg($requestImage))->resize(350, 190)->save($mediumImageUrlTwo, 80);
                    Image::make(imagecreatefromjpeg($requestImage))->resize(255, 175)->save($mediumImageUrlThree, 80);
                    Image::make(imagecreatefromjpeg($requestImage))->fit(123, 83)->save($smallImageUrl, 80);

                //PNG, png compression
                elseif ($fileType == 'PNG' or $fileType == 'png'):
                    Image::make(imagecreatefrompng($requestImage))->save($originalImageUrl, 80);

                    Image::make(imagecreatefrompng($requestImage))->fit(150, 150)->save($thumbnailImageUrl, 80);
                    Image::make(imagecreatefrompng($requestImage))->resize(1080, 1000)->save($bigImageUrl, 80);
                    Image::make(imagecreatefrompng($requestImage))->resize(730, 400)->save($bigImageUrlTwo, 80);
                    Image::make(imagecreatefrompng($requestImage))->resize(358, 215)->save($mediumImageUrl, 80);
                    Image::make(imagecreatefrompng($requestImage))->resize(350, 190)->save($mediumImageUrlTwo, 80);
                    Image::make(imagecreatefrompng($requestImage))->resize(255, 175)->save($mediumImageUrlThree, 80);
                    Image::make(imagecreatefrompng($requestImage))->fit(123, 83)->save($smallImageUrl, 80);
                endif;
            endif;

            $image->original_image      = str_replace("public/","",$originalImageUrl);
            $image->og_image            = str_replace("public/","",$ogImageUrl);
            $image->thumbnail           = str_replace("public/","",$thumbnailImageUrl);
            $image->big_image           = str_replace("public/","",$bigImageUrl);
            $image->big_image_two       = str_replace("public/","",$bigImageUrlTwo);
            $image->medium_image        = str_replace("public/","",$mediumImageUrl);
            $image->medium_image_two    = str_replace("public/","",$mediumImageUrlTwo);
            $image->medium_image_three  = str_replace("public/","",$mediumImageUrlThree);
            $image->small_image         = str_replace("public/","",$smallImageUrl);

            $image->disk                = settingHelper('default_storage');

            $image->save();

            $image                      = galleryImage::latest()->first();

            $data['status']             = 'success';
            $data['data']               = $image;

            if($image->thumbnail == ''){
                if (strpos(php_sapi_name(), 'cli') !== false || settingHelper('default_storage') =='s3' || defined('LARAVEL_START_FROM_PUBLIC')) :
                    $thumbnail = 'default-image/default-100x100.png';
                else:
                    $thumbnail = 'public/default-image/default-100x100.png';
                endif;
            }else{
                if (strpos(php_sapi_name(), 'cli') !== false || settingHelper('default_storage') =='s3' || defined('LARAVEL_START_FROM_PUBLIC')) :
                    $thumbnail = $image->thumbnail;
                else:
                    $thumbnail = 'public'.'/'.$image->thumbnail;
                endif;
            }
            return Response()->json([$data, $thumbnail]);

        else :
            $data['status']                 = 'error';
            $data['message']                = __('upload_failed');

            return Response()->json($data);
        endif;
    }

    public function deleteImage(Request $request){

        $image=galleryImage::find($request->row_id);

        if($image->disk =='s3') :

            if (Storage::disk('s3')->exists($image->original_image) && !blank($image->original_image)) :
                Storage::disk('s3')->delete($image->original_image);
            endif;
            if (Storage::disk('s3')->exists($image->og_image) && !blank($image->og_image)) :
                Storage::disk('s3')->delete($image->og_image);
            endif;
            if (Storage::disk('s3')->exists($image->thumbnail) && !blank($image->thumbnail)) :
                Storage::disk('s3')->delete($image->thumbnail);
            endif;
            if (Storage::disk('s3')->exists($image->big_image) && !blank($image->big_image)) :
                Storage::disk('s3')->delete($image->big_image);
            endif;
            if (Storage::disk('s3')->exists($image->big_image_two) && !blank($image->big_image_two)) :
                Storage::disk('s3')->delete($image->big_image_two);
            endif;
            if (Storage::disk('s3')->exists($image->medium_image) && !blank($image->medium_image)) :
                Storage::disk('s3')->delete($image->medium_image);
            endif;
            if (Storage::disk('s3')->exists($image->medium_image_two) && !blank($image->medium_image_two)) :
                Storage::disk('s3')->delete($image->medium_image_two);
            endif;
            if (Storage::disk('s3')->exists($image->medium_image_three) && !blank($image->medium_image_three)) :
                Storage::disk('s3')->delete($image->medium_image_three);
            endif;
            if (Storage::disk('s3')->exists($image->small_image) && !blank($image->small_image)) :
                Storage::disk('s3')->delete($image->small_image);
            endif;

            $image->delete();

            $data['status']         = "success";
            $data['message']        =  __('successfully_deleted');

        elseif($image->disk =='local'):
            if ($image->count() > 0) :

                //public path
                if (strpos(php_sapi_name(), 'cli') !== false || defined('LARAVEL_START_FROM_PUBLIC')) {
                    $path = '';
                }else{
                    $path = 'public/';
                }

                if (File::exists($path.$image->original_image) && !blank($image->original_image)) :
                    unlink($path.$image->original_image);
                endif;
                if (File::exists($path.$image->og_image) && !blank($image->og_image)) :
                    unlink($path.$image->og_image);
                endif;
                if (File::exists($path.$image->thumbnail) && !blank($image->thumbnail)) :
                    unlink($path.$image->thumbnail);
                endif;
                if (File::exists($path.$image->big_image) && !blank($image->big_image)) :
                    unlink($path.$image->big_image);
                endif;
                if (File::exists($path.$image->big_image_two) && !blank($image->big_image_two)) :
                    unlink($path.$image->big_image_two);
                endif;
                if (File::exists($path.$image->medium_image) && !blank($image->medium_image)) :
                    unlink($path.$image->medium_image);
                endif;
                if (File::exists($path.$image->medium_image_two) && !blank($image->medium_image_two)) :
                    unlink($path.$image->medium_image_two);
                endif;
                if (File::exists($path.$image->medium_image_three) && !blank($image->medium_image_three)) :
                    unlink($path.$image->medium_image_three);
                endif;
                if (File::exists($path.$image->small_image)) :
                    unlink($path.$image->small_image);
                endif;

                $image->delete();

                $data['status']     = "success";
                $data['message']    =  __('successfully_deleted');
            else :
                $data['status']     = "error";
                $data['message']    = __('not_found');
            endif;

        endif;

        echo json_encode($data);
    }

    public function videoUpload(Request $request)
    {
        $requestVideo           = $request->file('video');

        $validator = Validator::make($request->all(), [
            'video' => 'bail|required|mimes:mp4, 3gpp, webm',
        ])->validate();

        $fileType               = $requestVideo->getClientOriginalExtension();
        $videoName_original     = date('YmdHis') . "_original_" . rand(1, 50);

        if (strpos(php_sapi_name(), 'cli') !== false || defined('LARAVEL_START_FROM_PUBLIC')) :
            $directory              = 'videos/';
        else:
            $directory              = 'public/videos/';
        endif;

        $originalThumbUrl       = $directory . 'thumbnail/' . $videoName_original. '.jpg';
        $originalVideoUrl       = $directory . $videoName_original. '.' . $fileType;

        try {
            $video                  = new Video();
            $video->video_name      = $videoName_original;

            if(settingHelper('ffmpeg_status') == 1){
                $cmdForThumbnail        = "ffmpeg -i $requestVideo -ss 00:00:03.000 -vframes 1 $originalThumbUrl";
                exec($cmdForThumbnail);
                // save to public/video directory
                $video->video_thumbnail = str_replace("public/","", $originalThumbUrl);
            } else {
               $video->video_thumbnail  = '';
            }

            $saveOriginal           = $requestVideo->move($directory, $originalVideoUrl);
            $video->video_type      = $fileType;
            $video->original        = str_replace("public/","", $originalVideoUrl);
            $video->disk            = settingHelper('default_storage');
            $video->save();

            $video = Video::latest()->first();
            //check path for local public/''
            if (strpos(php_sapi_name(), 'cli') !== false || defined('LARAVEL_START_FROM_PUBLIC')) {
                $path = '';
            }else{
                $path = 'public/';
            }

            if(settingHelper('ffmpeg_status') == 1):

                if(settingHelper('default_storage') =='local') :
                    if (File::exists($path.$video->video_thumbnail)) :
                        $contents   = \File::get($path.$video->video_thumbnail);
                        $videoThumb = Image::make($contents)->fit(200, 200)->save($originalThumbUrl);
                    endif;
                endif;

                if(settingHelper('default_storage') =='s3'):
                    if (File::exists($path.$video->video_thumbnail)) :
                        $contents   = \File::get($path.$video->video_thumbnail);
                        $videoThumb = Image::make($contents)->fit(200, 200)->stream();
                        Storage::disk('s3')->put(str_replace("public/","", $originalThumbUrl), $videoThumb);
                        unlink($path.$video->video_thumbnail);
                    endif;
                endif;

            endif;

            $cron               = new Cron();
            $cron->cron_for     ='video_convert';
            $cron->video_id     = $video->id;
            $cron->save();

            if($video->video_thumbnail == ''){
                if (strpos(php_sapi_name(), 'cli') !== false || settingHelper('default_storage') =='s3' || defined('LARAVEL_START_FROM_PUBLIC')) :
                    $video_thumbnail = 'default-image/default-video-100x100.png';
                else:
                    $video_thumbnail = 'public/default-image/default-video-100x100.png';
                endif;
            }else{
                if (strpos(php_sapi_name(), 'cli') !== false || settingHelper('default_storage') =='s3' || defined('LARAVEL_START_FROM_PUBLIC')) :
                    $video_thumbnail = $video->video_thumbnail;
                else:
                    $video_thumbnail = 'public'.'/'.$video->video_thumbnail;
                endif;
            }

            return Response()->json([$video, $video_thumbnail]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function deleteVideo(Request $request){
        $video          = Video::find($request->row_id);
        if($video->disk =='s3') :
            if (Storage::disk('s3')->exists($video->original) && !blank($video->original)) :
                Storage::disk('s3')->delete($video->original);
            endif;
            if (Storage::disk('s3')->exists($video->v_144p) && !blank($video->v_144p)) :
                Storage::disk('s3')->delete($video->v_144p);
            endif;
            if (Storage::disk('s3')->exists($video->v_240p) && !blank($video->v_240p)) :
                Storage::disk('s3')->delete($video->v_240p);
            endif;
            if (Storage::disk('s3')->exists($video->v_360p) && !blank($video->v_360p)) :
                Storage::disk('s3')->delete($video->v_360p);
            endif;
            if (Storage::disk('s3')->exists($video->v_480p) && !blank($video->v_480p)) :
                Storage::disk('s3')->delete($video->v_480p);
            endif;
            if (Storage::disk('s3')->exists($video->v_720p) && !blank($video->v_720p)) :
                Storage::disk('s3')->delete($video->v_720p);
            endif;
            if (Storage::disk('s3')->exists($video->v_1080p) && !blank($video->v_1080p)) :
                Storage::disk('s3')->delete($video->v_1080p);
            endif;
            if (Storage::disk('s3')->exists($video->video_thumbnail) && !blank($video->video_thumbnail)) :
                Storage::disk('s3')->delete($video->video_thumbnail);
            endif;

            $video->delete();
            $data['status']     = "success";
            $data['message']    =  __('successfully_deleted');

        elseif($video->disk =='local'):
            if ($video->count() > 0) :
                if (strpos(php_sapi_name(), 'cli') !== false || defined('LARAVEL_START_FROM_PUBLIC')) {
                    $path = '';
                }else{
                    $path = 'public/';
                }
                if (File::exists($path.$video->original) && !blank($video->original)) :
                    unlink($path.$video->original);
                endif;
                if (File::exists($path.$video->v_144p) && !blank($video->v_144p)) :
                    unlink($path.$video->v_144p);
                endif;
                if (File::exists($path.$video->v_240p) && !blank($video->v_240p)) :
                    unlink($path.$video->v_240p);
                endif;
                if (File::exists($path.$video->v_360p) && !blank($video->v_360p)) :
                    unlink($path.$video->v_360p);
                endif;
                if (File::exists($path.$video->v_480p) && !blank($video->v_480p)) :
                    unlink($path.$video->v_480p);
                endif;
                if (File::exists($path.$video->v_720p) && !blank($video->v_720p)) :
                    unlink($path.$video->v_720p);
                endif;
                if (File::exists($path.$video->v_1080p) && !blank($video->v_1080p)) :
                    unlink($path.$video->v_1080p);
                endif;
                if (File::exists($path.$video->video_thumbnail) && !blank($video->video_thumbnail)) :
                    unlink($path.$video->video_thumbnail);
                endif;

                $video->delete();

                $data['status']     = "success";
                $data['message']    =  __('successfully_deleted');
            else :
                $data['status']     = "error";
                $data['message']    = __('not_found');
            endif;
        endif;

        echo json_encode($data);
    }

    public function audioUpload(Request $request){

        $requestAudio           = $request->file('audio');
        if ($requestAudio) :
            $validator = Validator::make($request->all(), [
                'audio' => 'required|mimes:mp3,wav',
            ])->validate();
            $audio                  = new Audio();

            $filename               = $requestAudio->getClientOriginalName();

            \Log::info('success');
            $fileType               = $requestAudio->getClientOriginalExtension();
            $audioName_original     = date('YmdHis') . "_original_audio" . rand(1, 50);

            if (strpos(php_sapi_name(), 'cli') !== false || settingHelper('default_storage') =='s3' || defined('LARAVEL_START_FROM_PUBLIC')) :
                $directory              = 'audios/';
            else:
                $directory              = 'public/audios/';
            endif;

            $originalAudioUrl       = $directory . $audioName_original. '.' . $fileType;
            if(settingHelper('default_storage') =='s3') :
                try {
//                    Storage::disk('s3')->put($originalAudioUrl, $requestAudio);
                    Storage::disk('s3')->put($originalAudioUrl, fopen($requestAudio, 'r+'), 'public');
                } catch(S3 $e) {
                    $data['status']     = 'error';
                    $data['message']    = $e->getMessage();

                    return Response()->json($data);
                }
            elseif(settingHelper('default_storage') =='local'):
                try {
                    $saveOriginal = $requestAudio->move($directory, $originalAudioUrl);
                }
                catch(\Exception $e){
                    $data['status']     = 'error';
                    $data['message']    = $e->getMessage();

                    return Response()->json($data);
                }
            endif;

            $audio->audio_name      = $filename;
            $audio->original        = $audioName_original;

            $audio->audio_type      = $fileType;
            $audio->original        = str_replace("public/","", $originalAudioUrl);
            $audio->disk            = settingHelper('default_storage');
            $audio->save();

            return Response()->json([$audio]);
        else :
            $data['status']                 = 'error';
            $data['message']                = __('upload_failed');

            return Response()->json($data);
        endif;
    }

    public function deleteAudio(Request $request){

        $audio         = Audio::find($request->row_id);
        if($audio->disk =='s3') :
            if (Storage::disk('s3')->exists($audio->original) && !blank($audio->original)) :
                Storage::disk('s3')->delete($audio->original);
            endif;
            if (Storage::disk('s3')->exists($audio->audio_mp3) && !blank($audio->audio_mp3)) :
                Storage::disk('s3')->delete($audio->audio_mp3);
            endif;
            if (Storage::disk('s3')->exists($audio->audio_ogg) && !blank($audio->audio_ogg)) :
                Storage::disk('s3')->delete($audio->audio_ogg);
            endif;

            $audio->delete();
            $audio->posts()->detach();
            $data['status']     = "success";
            $data['message']    =  __('successfully_deleted');

        elseif($audio->disk =='local'):
            if ($audio->count() > 0) :
                if (strpos(php_sapi_name(), 'cli') !== false || defined('LARAVEL_START_FROM_PUBLIC')) {
                    $path = '';
                }else{
                    $path = 'public/';
                }

                if (File::exists($path.$audio->original) && !blank($audio->original)) :
                    unlink($path.$audio->original);
                endif;
                if (File::exists($path.$audio->audio_mp3) && !blank($audio->audio_mp3)) :
                    unlink($path.$audio->audio_mp3);
                endif;
                if (File::exists($path.$audio->audio_ogg) && !blank($audio->audio_ogg)) :
                    unlink($path.$audio->audio_ogg);
                endif;
                $audio->posts()->detach();
                $audio->delete();
                $audio->posts()->detach();
                $data['status']     = "success";
                $data['message']    =  __('successfully_deleted');
            else :
                $data['status']     = "error";
                $data['message']    = __('not_found');
            endif;
        endif;

        echo json_encode($data);
    }
    public function fetchImage(Request $request)
    {

        $content_count = '';
        if(isset($request->content_count)){
            $content_count = $request->content_count;
        }

        $images         = galleryImage::orderBy('id','DESC')->paginate(24);

        return view('gallery::ajax-images', compact('images', 'content_count'));
    }

    public function fetchVideo(Request $request)
    {
        $content_count = '';
        if(isset($request->content_count)){
            $content_count = $request->content_count;
        }

        $videos         = Video::orderBy('id','DESC')->paginate(24);

        return view('gallery::ajax-videos', compact('videos', 'content_count'));

    }
    public function fetchAudio()
    {
        $audios        = Audio::orderBy('id','DESC')->paginate(24);

        return view('gallery::ajax-audios',compact('audios'));

    }
}
