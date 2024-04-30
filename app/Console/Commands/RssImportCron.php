<?php

namespace App\Console\Commands;

use Aws\S3\Exception\S3Exception as S3;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Modules\Common\Entities\Cron;
use Modules\Gallery\Entities\Image as galleryImage;
use Modules\Post\Entities\Post;
use Modules\Post\Entities\RssFeed;
use Sentinel;
use Image;

class RssImportCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rssImport:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for rss post importing';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $rssFeeds      = RssFeed::where('auto_update' , 1)->get();
        foreach ($rssFeeds as $feed) {
            $invalidUrl = false;
            if(@simplexml_load_file($feed->feed_url)):
                $feeds = simplexml_load_file($feed->feed_url, null, LIBXML_NOCDATA);
                $namespaces = $feeds->getNamespaces(true);

                if(!empty($feeds)):
                    $language = $feeds->channel->language;

                    $i=0;
                    foreach ($feeds->channel->item as $key => $item) :
                        $post = new Post();
                        $slug = $this->make_slug($item->title);
                        $hasAlready = Post::where('title',$item->title)->orwhere('slug', $slug)->first();

                        if (!empty($hasAlready)){
                            continue;
                        }

                        if($feed->post_limit == $i):
                            break;
                        endif;

                        $post->title        = $item->title;
                        $post->slug         = $slug;

//                    $link           = $item->link;
                        $post->content      = $item->description;

                        if($feed->keep_date):
                            $post->created_at       = Carbon::parse($item->pubDate);
                        endif;

                        if($feed->show_read_more):
                            $post->read_more_link   = $item->link;
                        endif;

                        $post->language         = $feed->language;
                        $post->category_id      = $feed->category_id;
                        $post->sub_category_id  = $feed->sub_category_id ;
                        $post->layout           = $feed->layout ;

                        if($feed->status == 2) :
                            $post->status           = 0;
                            $post->scheduled        = 1;
                            $post->scheduled_date   = $feed->scheduled_date;
                            $post->visibility       = 1;
                        elseif($feed->status == 0):
                            $post->status     = $feed->status;
                        else :
                            $post->status           = $feed->status;
                            $post->visibility       = 1;
                        endif;

                        $post->user_id          = 1;
                        $post->tags             = $feed->tags ;
                        $post->meta_keywords    = $feed->meta_keywords ;
                        $post->meta_description = $feed->meta_description ;

                        if(!empty($this->getImg($item , $namespaces))):

                            if(preg_match('/\.(jpg|png|jpeg|PNG|JPEG|JPG)$/', $this->getImg($item , $namespaces))):
                                $post->post_type        = 'article' ;

                                $post->image_id = $this->imageUpload(
                                    $this->character_convert(
                                        $this->getImg($item , $namespaces)
                                    )
                                );
                            elseif(preg_match('/(jpg|png|jpeg|PNG|JPEG|JPG)$/', $this->getType($item))):
                                $post->post_type        = 'article' ;
                                $post->image_id = $this->imageUpload($this->character_convert($this->getImg($item , $namespaces)) ,$this->getType($item));
                            elseif(preg_match('/\.(mp4|3gp|webm)$/', $this->getImg($item , $namespaces))):
                                $post->post_type        = 'video' ;
                                $post->video_url_type   = 'mp4_url' ;

                                $post->video_url = $this->character_convert($this->getImg($item , $namespaces));
                            else:
                                $post->post_type        = 'article' ;
                            endif;
                        else:
                            $post->post_type        = 'article' ;
                        endif;

                        $post->save();
                        $i++;
                    endforeach;

                    Cache::Flush();
                endif;
            else:
                $invalidurl = true;
                continue;
            endif;
        }
    }

    public function getImg($item , $namespaces){

        if(@$item->enclosure):
            return $this->character_convert($item->enclosure['url']);
        elseif(@$item->fullimage):
            return $this->character_convert($item->fullimage);
        elseif(@$namespaces['media']):
            $media_content = $item->children($namespaces['media'])  ;
            if($media_content->group->content):
                foreach($media_content->group->content as $i){
                    return (((string)$i->attributes()->url));
                }
            elseif($media_content->thumbnail):
                foreach($media_content->thumbnail as $i){
                    return(((string)$i->attributes()->url));
                }
            endif;
        else:
            return '';
        endif;
    }

    public function getType($item = ''){
        return $this->character_convert(preg_replace("/img\//", "", @$item->enclosure['type']));
    }

    public function character_convert($str)
    {
        $str = trim($str);
        $str = str_replace("&amp;", "&", $str);
        $str = str_replace("&lt;", "<", $str);
        $str = str_replace("&gt;", ">", $str);
        $str = str_replace("&quot;", '"', $str);
        $str = str_replace("&apos;", "'", $str);
        return $str;
    }

    private function make_slug($string, $delimiter = '-') {

        $string = preg_replace("/[~`{}.'\"\!\@\#\$\%\^\&\*\(\)\_\=\+\/\?\>\<\,\[\]\:\;\|\\\]/", "", $string);

        $string = preg_replace("/[\/_|+ -]+/", $delimiter, $string);
        $result = mb_strtolower($string);

        if ($result):
            return $result;
        else:
            return $string;
        endif;
    }

    public function imageUpload($request, $type ='')
    {
        try {
            $image = new galleryImage();
            $requestImage = $request;
            $fileType = preg_replace("/.*\./","",$requestImage);

            if($type != ''){
                $fileType = $type;
            }
//            dd($fileType);

            $originalImageName = date('YmdHis') . "_original_" . rand(1, 50) . '.' . 'webp';
            $ogImageName = date('YmdHis') . "_ogImage_" . rand(1, 50) . '.' . $fileType;
            $thumbnailImageName = date('YmdHis') . "_thumbnail_100x100_" . rand(1, 50) . '.' . 'webp';
            $bigImageName = date('YmdHis') . "_big_1080x1000_" . rand(1, 50) . '.' . 'webp';
            $bigImageNameTwo = date('YmdHis') . "_big_730x400_" . rand(1, 50) . '.' . 'webp';
            $mediumImageName = date('YmdHis') . "_medium_358x215_" . rand(1, 50) . '.' . 'webp';
            $mediumImageNameTwo = date('YmdHis') . "_medium_350x190_" . rand(1, 50) . '.' . 'webp';
            $mediumImageNameThree = date('YmdHis') . "_medium_255x175_" . rand(1, 50) . '.' . 'webp';
            $smallImageName = date('YmdHis') . "_small_123x83_" . rand(1, 50) . '.' . 'webp';


            if (strpos(php_sapi_name(), 'cli') !== false || settingHelper('default_storage') == 's3' || defined('LARAVEL_START_FROM_PUBLIC')) :
                $directory = 'images/';
            else:
                $directory = 'public/images/';
            endif;

            $originalImageUrl = $directory . $originalImageName;
            $ogImageUrl = $directory . $ogImageName;
            $thumbnailImageUrl = $directory . $thumbnailImageName;
            $bigImageUrl = $directory . $bigImageName;
            $bigImageUrlTwo = $directory . $bigImageNameTwo;
            $mediumImageUrl = $directory . $mediumImageName;
            $mediumImageUrlTwo = $directory . $mediumImageNameTwo;
            $mediumImageUrlThree = $directory . $mediumImageNameThree;
            $smallImageUrl = $directory . $smallImageName;


            if (settingHelper('default_storage') == 's3'):

                //ogImage
                $imgOg = Image::make($requestImage)->fit(730, 400)->stream();

                //jpg. jpeg, JPEG, JPG compression
                if ($fileType == 'jpeg' or $fileType == 'jpg' or $fileType == 'JPEG' or $fileType == 'JPG'):
                    $imgOriginal = Image::make(imagecreatefromjpeg($requestImage))->encode('webp', 80);
                    $imgThumbnail = Image::make(imagecreatefromjpeg($requestImage))->fit(100, 100)->encode('webp', 80);
                    $imgBig = Image::make(imagecreatefromjpeg($requestImage))->fit(1080, 1000)->encode('webp', 80);
                    $imgBigTwo = Image::make(imagecreatefromjpeg($requestImage))->fit(730, 400)->encode('webp', 80);
                    $imgMedium = Image::make(imagecreatefromjpeg($requestImage))->fit(358, 215)->encode('webp', 80);
                    $imgMediumTwo = Image::make(imagecreatefromjpeg($requestImage))->fit(350, 190)->encode('webp', 80);
                    $imgMediumThree = Image::make(imagecreatefromjpeg($requestImage))->fit(255, 175)->encode('webp', 80);
                    $imgSmall = Image::make(imagecreatefromjpeg($requestImage))->fit(123, 83)->encode('webp', 80);

                //png compression
                elseif ($fileType == 'PNG' or $fileType == 'png'):

                    $imgOriginal = Image::make(imagecreatefrompng($requestImage))->encode('webp', 80);
                    $imgThumbnail = Image::make(imagecreatefrompng($requestImage))->fit(100, 100)->encode('webp', 80);
                    $imgBig = Image::make(imagecreatefrompng($requestImage))->fit(1080, 1000)->encode('webp', 80);
                    $imgBigTwo = Image::make(imagecreatefrompng($requestImage))->fit(730, 400)->encode('webp', 80);
                    $imgMedium = Image::make(imagecreatefrompng($requestImage))->fit(358, 215)->encode('webp', 80);
                    $imgMediumTwo = Image::make(imagecreatefrompng($requestImage))->fit(350, 190)->encode('webp', 80);
                    $imgMediumThree = Image::make(imagecreatefrompng($requestImage))->fit(255, 175)->encode('webp', 80);
                    $imgSmall = Image::make(imagecreatefrompng($requestImage))->fit(123, 83)->encode('webp', 80);

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

                } catch (S3 $e) {
                    $data['status'] = 'error';
                    $data['message'] = $e->getMessage();
                    return Response()->json($data);
                }
            elseif (settingHelper('default_storage') == 'local'):

                Image::make($requestImage)->fit(730, 400)->save(public_path($ogImageUrl));

                if ($fileType == 'jpeg' or $fileType == 'jpg' or $fileType == 'JPEG' or $fileType == 'JPG'):
                    Image::make(imagecreatefromjpeg($requestImage))->save(public_path($originalImageUrl), 80);

                    Image::make(imagecreatefromjpeg($requestImage))->fit(100, 100)->save(public_path($thumbnailImageUrl), 80);
                    Image::make(imagecreatefromjpeg($requestImage))->fit(1080, 1000)->save(public_path($bigImageUrl), 80);
                    Image::make(imagecreatefromjpeg($requestImage))->fit(730, 400)->save(public_path($bigImageUrlTwo), 80);
                    Image::make(imagecreatefromjpeg($requestImage))->fit(358, 215)->save(public_path($mediumImageUrl), 80);
                    Image::make(imagecreatefromjpeg($requestImage))->fit(350, 190)->save(public_path($mediumImageUrlTwo), 80);
                    Image::make(imagecreatefromjpeg($requestImage))->fit(255, 175)->save(public_path($mediumImageUrlThree), 80);
                    Image::make(imagecreatefromjpeg($requestImage))->fit(123, 83)->save(public_path($smallImageUrl), 80);

                elseif ($fileType == 'PNG' or $fileType == 'png'):
                    Image::make(imagecreatefrompng($requestImage))->save(public_path($originalImageUrl), 80);

                    Image::make(imagecreatefrompng($requestImage))->fit(100, 100)->save(public_path($thumbnailImageUrl), 80);
                    Image::make(imagecreatefrompng($requestImage))->fit(1080, 1000)->save(public_path($bigImageUrl), 80);
                    Image::make(imagecreatefrompng($requestImage))->fit(730, 400)->save(public_path($bigImageUrlTwo), 80);
                    Image::make(imagecreatefrompng($requestImage))->fit(358, 215)->save(public_path($mediumImageUrl), 80);
                    Image::make(imagecreatefrompng($requestImage))->fit(350, 190)->save(public_path($mediumImageUrlTwo), 80);
                    Image::make(imagecreatefrompng($requestImage))->fit(255, 175)->save(public_path($mediumImageUrlThree), 80);
                    Image::make(imagecreatefrompng($requestImage))->fit(123, 83)->save(public_path($smallImageUrl), 80);
                endif;
            endif;

            $image->original_image = str_replace("public/", "", $originalImageUrl);
            $image->og_image = str_replace("public/", "", $ogImageUrl);
            $image->thumbnail = str_replace("public/", "", $thumbnailImageUrl);
            $image->big_image = str_replace("public/", "", $bigImageUrl);
            $image->big_image_two = str_replace("public/", "", $bigImageUrlTwo);
            $image->medium_image = str_replace("public/", "", $mediumImageUrl);
            $image->medium_image_two = str_replace("public/", "", $mediumImageUrlTwo);
            $image->medium_image_three = str_replace("public/", "", $mediumImageUrlThree);
            $image->small_image = str_replace("public/", "", $smallImageUrl);

            $image->disk = settingHelper('default_storage');
            $image->save();
            $image = galleryImage::latest()->first();

            return $image->id;
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return null;
        }
    }

}
