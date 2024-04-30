<?php

namespace Modules\Api\Http\Controllers;

use App\Enums\VisitorPageType;
use App\Traits\PostAttributeSetTrait;
use App\VisitorTracker;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Config;
use Modules\Ads\Entities\Ad;
use Modules\Gallery\Entities\Image as galleryImage;
use Modules\Gallery\Entities\Video;
use Modules\Post\Entities\Category;
use Modules\Post\Entities\Comment;
use Validator;
use Modules\Post\Entities\Post;
use App\Traits\ApiReturnFormat;
use Sentinel;
use DB;
use function GuzzleHttp\Promise\exception_for;

class PostController extends Controller
{
    use ApiReturnFormat;
    use PostAttributeSetTrait;

    public function searchPost(Request $request){

        $validator = Validator::make($request->all(), [
            'search' => 'required',
        ]);

        if($validator->fails()){
            return $this->responseWithError(__('required_field_missing'), $validator->errors(), 422);
        }
        $language   = $request->lang ?? settingHelper('default_language');

        $posts = Post::with('image','video','category:id,category_name','user:id,first_name,last_name,email')
            ->Where('title', 'like', '%' . $request->search . '%')
            ->where('status',1)
            ->where('language',$language)
            ->select('id','category_id','user_id','image_id','video_id','title','slug','content','created_at')
            ->orderBy('slider_order')
            ->paginate(15);

        return $this->responseWithSuccess(__('successfully_found'),$this->imageUrlset($this->commentsCount($this->dateToHuman($posts->items()))), 200);

    }
    public function postDetails($slug){
        $language   = $request->lang ?? settingHelper('default_language');

        $post = Post::with(['image','video','category:id,category_name','user:id,first_name,last_name,email',
            'comments' => function($q){
                $q->select('id','post_id','user_id','comment_id','comment','status');
                $q->where('status',1);
                $q->where('comment_id',null);
            },
            'comments.replay' => function($q){
                $q->select('id','post_id','user_id','comment_id','comment','status');
                $q->where('status',1);
            },
            'comments.user:id,first_name,last_name,email,image_id',
            'comments.replay.user:id,first_name,last_name,email,image_id',
            'comments.user.image'
        ])
            ->Where('slug', $slug)
            ->where('status',1)
            ->where('language',$language)
            // ->select('id','category_id','user_id','image_id','video_id','title','slug','content','tags','created_at')
            ->first();

        $categoryId = $post->category_id;
        $related_post = Post::where('category_id', $categoryId)
            ->with('image','video','category:id,category_name','user:id,first_name,last_name,email')
            ->where('status',1)
            ->where('language',$language)
            ->select('id','category_id','user_id','image_id','video_id','title','slug','content','created_at')
            ->orderBy('created_at','DESC')
            ->take(8)
            ->get();

        $detailsPage['post_details'] = $post;
        $detailsPage['related_post'] = $related_post;

        return $this->responseWithSuccess(__('successfully_found'),$detailsPage, 200);
    }

    public function articleDetail(Request $request, $id){

        if(!Post::find($id)):
            return $this->responseWithError(__('post_not_found'),[],404);
        endif;

        $post = Post::with(['image:id,og_image','video','category:id,category_name,is_featured','user:id,first_name,last_name,email,social_media,profile_image','audio'])
            ->Where('id', $id)
            ->where('status',1)
            ->select('id','category_id','language','user_id','image_id','post_type','video_id','video_url_type','video_url','video_thumbnail_id','title','slug','content','contents','tags','created_at')
            ->first();

        if (isset($post['tags'])):
            $post['tags'] = explode (",", $post['tags']);
        else:
            $post['tags'] = [];
        endif;

        $post['url'] = route('article.detail', ['id' => $post['slug']]);

        if(isset($post['contents'])):
            foreach ($post['contents'] as $content):

                if(isset($content['text'])):
                    if ($text = $content['text']['text']):
                        unset($content['text']);
                        $content['type'] = 'text';
                        $content['text'] = $text;
                    endif;
                endif;

                if(isset($content['image'])):
                    if ($content['image']['image_id']) :
                        $image = galleryImage::find($content['image']['image_id']);
                        unset($content['image']);
                        $content['type'] = 'image';
                        $content['image'] = $this->get_image($image);
                    endif;
                endif;

                if(isset($content['image-text'])):
                    if ($content['image-text']['image_id']) :
                        $image = galleryImage::find($content['image-text']['image_id']);

                        $text = $content['image-text']['text'];

                        unset($content['image-text']);

                        $content['type'] = 'image-text';
                        $content['image'] = $this->get_image($image);
                        $content['text'] = $text;

                    endif;
                endif;

                if(isset($content['text-image'])):
                    if ($content['text-image']['image_id']) :
                        $image = galleryImage::find($content['text-image']['image_id']);

                        $text = $content['text-image']['text'];

                        unset($content['text-image']);
                        $content['type'] = 'text-image';
                        $content['text'] = $text;
                        $content['image'] = $this->get_image($image);

                    endif;
                endif;

                if(isset($content['text-image-text'])):
                    if ($content['text-image-text']['image_id']) :
                        $image = galleryImage::find($content['text-image-text']['image_id']);

                        $content['type'] = 'text-image-text';
                        $content['text_1'] = $content['text-image-text']['text_1'];
                        $content['image'] = $this->get_image($image);
                        $content['text_2'] = $content['text-image-text']['text_2'];

                        unset($content['text-image-text']);

                    endif;
                endif;

                if(isset($content['ads'])):
                    if ($content['ads']['ads']) :

                        $content['type'] = 'ads';

                        $ads_info = Ad::find($content['ads']['ads']);

                        $content['ad_type'] = $ads_info->ad_type;

                        if($ads_info->ad_type == 'image'):
                            $image = galleryImage::find($ads_info->ad_image_id);
                            $content['image'] = $this->get_image($image);
                            $content['ad_url'] = $ads_info->ad_url;

                        elseif($ads_info->ad_type == 'code'):
                            $content['code'] = $ads_info->ad_code;

                        elseif($ads_info->ad_type == 'text'):
                            $content['text'] = $ads_info->ad_text;

                        endif;

                        unset($content['ads']);

                    endif;
                endif;

                if(isset($content['youtube-embed'])):
                    if ($content['youtube-embed']['youtube']) :
                        $content['type'] = 'youtube-embed';
                        $content['youtube_id'] = $this->get_id_youtube($content['youtube-embed']['youtube']);
                        unset($content['youtube-embed']);
                    endif;
                endif;

                if(isset($content['twitter-embed'])):
                    if ($content['twitter-embed']['twitter']) :
                        $content['type'] = 'twitter-embed';
                        $content['embed-code'] = $content['twitter-embed']['twitter'];
                        unset($content['twitter-embed']);
                    endif;
                endif;

                if(isset($content['vimeo-embed'])):
                    if ($content['vimeo-embed']['vimeo']) :
                        $content['type'] = 'vimeo-embed';
                        $content['embed-code'] = $content['vimeo-embed']['vimeo'];
                        unset($content['vimeo-embed']);
                    endif;
                endif;

                if(isset($content['code'])):
                    if ($code= $content['code']['code']) :

                        unset($content['code']);

                        $content['type'] = 'code';
                        $content['code'] = $code;
                    endif;
                endif;

                if(isset($content['video'])):
                    $video = $content['video'];

                    unset($content['video']);

                    $content['type'] = 'video';
                    $content['video'] = $this->additionalVideoUrlSet($video);
                endif;

                $response[] = $content;
            endforeach;
        else:
            $response = [];
        endif;

        unset($post['contents']);
        $post['additional_contents'] = $response;

        if (isset($post->user['profile_image'])):
            $post->user['image'] = static_asset($post->user['profile_image']);
            unset($post->user['profile_image']);
        endif;

        if (isset($post->created_at)):
            $post->created = Carbon::parse($post->created_at)->diffForHumans();
        endif;

        if (isset($post->id)):
            $post->commentsCount = Comment::where('post_id', $post->id)->where('comment_id', '=' , null)->count();
        endif;

        if (isset($post->image)) {
            if ($post->image->disk == 's3') {
                $s3Link = "https://s3." . Config::get('filesystems.disks.s3.region') . ".amazonaws.com/" . Config::get('filesystems.disks.s3.bucket') . "/";

                $post->image->og_image = $s3Link . $post->image->og_image;

            } else {
                $post->image->og_image = static_asset($post->image->og_image);
            }
        }
        if($post->post_type == "video"):
            if (isset($post->video)) {
                $this->videoUrlSet($post);
            }
        endif;

        if ($post->post_type == "audio") {
            unset($post->video_id);
            unset($post->video_url_type);
            unset($post->video_url);
            unset($post->video_thumbnail_id);
            unset($post->video);

            $this->audioUrlSet($post);
        }

        if ($post->post_type == "article") {
            unset($post->video_id);
            unset($post->video_url_type);
            unset($post->video_url);
            unset($post->video_thumbnail_id);
            unset($post->video);
        }

        $categoryId = $post->category_id;
        $language   = $request->lang ?? settingHelper('default_language');

        $related_post = Post::with('image','category:id,category_name,slug','user:id,first_name,last_name')
            ->where('visibility', 1)
            ->where('status', 1)
            ->where('category_id', $categoryId)
            ->where('language',$language)
            ->when(Sentinel::check() == false, function ($query) {
                $query->where('auth_required', 0);
            })
            ->select('id','category_id','image_id','user_id','title','slug','post_type','created_at')
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();

        $post->related_posts = $this->imageUrlset($this->commentsCount($this->dateToHuman($related_post)));

        if ($post->category->is_featured == true):
            $post->related_topic = Category::where('language', $language)->where('is_featured', true)->where('id', '!=', $categoryId)->select('id','category_name','slug')->get();
        else:
            $post->related_topic = Category::where('language', $language)->where('is_featured', false)->where('id', '!=', $categoryId)->select('id','category_name','slug')->get();
        endif;

        $tracker = new VisitorTracker();
        $tracker->page_type = \App\Enums\VisitorPageType::PostDetailPage;
        $tracker->slug = $post->slug;
        $tracker->url = \Request::url();
        $tracker->source_url = \url()->previous();
        $tracker->ip = \Request()->ip();
        $tracker->date = date('Y-m-d');
        $tracker->agent_browser = UserAgentBrowser(\Request()->header('User-Agent'));
        $tracker->save();

        return $post;
    }

    public function latestPosts(Request $request){
        $language   = $request->lang ?? settingHelper('default_language');

        $posts = Post::with('image','category:id,category_name,slug','user:id,first_name,last_name')
            ->where('visibility', 1)
            ->where('status', 1)
            ->where('language',$language)
            ->when(Sentinel::check() == false, function ($query) {
                $query->where('auth_required', 0);
            })
            ->select('id','category_id','image_id','user_id','title','slug','post_type','created_at')
            ->orderBy('id', 'desc')
            ->paginate(10);

        return $this->responseWithSuccess(__('successfully_found'),$this->imageUrlset($this->commentsCount($this->dateToHuman($posts->items()))), 200);
    }

    public function videoPosts(Request $request){

        $language   = $request->lang ?? settingHelper('default_language');

        $posts = Post::with('image','category:id,category_name,slug','user:id,first_name,last_name')
            ->where('visibility', 1)
            ->where('post_type', 'video')
            ->where('status', 1)
            ->where('language',$language)
            ->when(Sentinel::check() == false, function ($query) {
                $query->where('auth_required', 0);
            })
            ->select('id','category_id','image_id','user_id','title','slug','post_type','created_at')
            ->orderBy('id', 'desc')
            ->paginate(10);

        return $this->responseWithSuccess(__('successfully_found'),$this->imageUrlset($this->commentsCount($this->dateToHuman($posts->items()))),200);
    }

    public function postByCategory(Request $request, $id){

        $language   = $request->lang ?? settingHelper('default_language');

        $posts = Post::with('image','user:id,first_name,last_name')
            ->where('visibility', 1)
            ->where('status', 1)
            ->where('language',$language)
            ->when(Sentinel::check() == false, function ($query) {
                $query->where('auth_required', 0);
            })
            ->select('id','category_id','image_id','user_id','title','slug','post_type','created_at')
            ->where('category_id',$id)
            ->orderBy('id', 'desc')
            ->paginate(10);

        return $this->responseWithSuccess(__('successfully_found'),$this->imageUrlset($this->commentsCount($this->dateToHuman($posts->items()))), 200);
    }

    public function postBySubCategory(Request $request, $id){

        $language   = $request->lang ?? settingHelper('default_language');

        $posts = Post::with('image','user:id,first_name,last_name')
            ->where('visibility', 1)
            ->where('status', 1)
            ->where('language',$language)
            ->when(Sentinel::check() == false, function ($query) {
                $query->where('auth_required', 0);
            })
            ->select('id','category_id','sub_category_id','image_id','user_id','title','slug','post_type','created_at')
            ->where('sub_category_id',$id)
            ->orderBy('id', 'desc')
            ->paginate(10);

        return $this->responseWithSuccess(__('successfully_found'),$this->imageUrlset($this->commentsCount($this->dateToHuman($posts->items()))), 200);
    }

    public function postByTag(Request $request, $slug){

        $language   = $request->lang ?? settingHelper('default_language');

        $posts = Post::with('image','user:id,first_name,last_name')
            ->where('visibility', 1)
            ->where('status', 1)
            ->where('language',$language)
            ->whereRaw("FIND_IN_SET('$slug',tags)")
            ->when(Sentinel::check() == false, function ($query) {
                $query->where('auth_required', 0);
            })
            ->select('id','category_id','sub_category_id','image_id','user_id','title','slug','post_type','created_at')
            ->orderBy('id', 'desc')
            ->paginate(10);

        return $this->responseWithSuccess(__('successfully_found'),$this->imageUrlset($this->commentsCount($this->dateToHuman($posts->items()))), 200);
    }

    public function postByAuthor(Request $request, $id){

        $language   = $request->lang ?? settingHelper('default_language');

        $posts = Post::with('image','user:id,first_name,last_name')
            ->where('visibility', 1)
            ->where('status', 1)
            ->where('language',$language)
            ->where('user_id',$id)
            ->when(Sentinel::check() == false, function ($query) {
                $query->where('auth_required', 0);
            })
            ->select('id','category_id','sub_category_id','image_id','user_id','title','slug','post_type','created_at')
            ->orderBy('id', 'desc')
            ->paginate(10);

        return $this->responseWithSuccess(__('successfully_found'),$this->imageUrlset($this->commentsCount($this->dateToHuman($posts->items()))), 200);
    }

    public function postByDate(Request $request, $date){

        $language   = $request->lang ?? settingHelper('default_language');

        $posts = Post::with('image','user:id,first_name,last_name')
            ->where('visibility', 1)
            ->where('status', 1)
            ->where('language',$language)
            ->whereDate('created_at', $date)
            ->when(Sentinel::check() == false, function ($query) {
                $query->where('auth_required', 0);
            })
            ->select('id','category_id','sub_category_id','image_id','user_id','title','slug','post_type','created_at')
            ->orderBy('id', 'desc')
            ->paginate(10);

        return $this->responseWithSuccess(__('successfully_found'),$this->imageUrlset($this->commentsCount($this->dateToHuman($posts->items()))), 200);
    }

    public function getVideoPosts(Request $request){
        $language   = $request->lang ?? settingHelper('default_language');

        //latest posts
        $home_content['latest_posts']['cat_type']        = 'latest_posts';
        $home_content['latest_posts']['cat_title']       = __('latest_posts');
        $home_content['latest_posts']['sliders']         = $this->imageUrlset($this->commentsCount($this->dateToHuman($this->latestPostsVideo($language,5))));
        $home_content['latest_posts']['articles']        = $this->imageUrlset($this->commentsCount($this->dateToHuman($this->latestPostsVideo($language, 10, 5))));
        $home_content['latest_posts']['trending_posts']  = $this->trendingPosts($language);
        $tags = '';

        foreach($home_content['latest_posts']['sliders'] as $post):
            $tags .= ($tags == '' ?'': ',').$post->tags;
        endforeach;

        foreach($home_content['latest_posts']['articles'] as $post):
            $tags .= ($tags == '' ?'': ',').$post->tags;
        endforeach;

        foreach($home_content['latest_posts']['trending_posts'] as $post):
            $tags .= ($tags == '' ?'': ',').$post->tags;
        endforeach;
        $tags = implode(',', array_unique(explode(',', $tags)));
        $tags = explode(',', $tags);
        $home_content['latest_posts']['tags'] = $tags;


        //top stories
        $home_content['top_stories']['cat_type']        = 'top_stories';
        $home_content['top_stories']['cat_title']       = __('top_stories');
        $home_content['top_stories']['sliders']         = $this->imageUrlset($this->commentsCount($this->dateToHuman($this->popularPost($language, 5))));
        $home_content['top_stories']['articles']        = $this->imageUrlset($this->commentsCount($this->dateToHuman($this->popularPost($language, 10, 5))));
        $home_content['top_stories']['trending_posts']  = $this->trendingPosts($language);
        $tags = '';

        foreach($home_content['top_stories']['sliders'] as $post):
            $tags .= ($tags == '' ?'': ',').$post->tags;
        endforeach;

        foreach($home_content['top_stories']['articles'] as $post):
            $tags .= ($tags == '' ?'': ',').$post->tags;
        endforeach;

        foreach($home_content['top_stories']['trending_posts'] as $post):
            $tags .= ($tags == '' ?'': ',').$post->tags;
        endforeach;
        $tags = implode(',', array_unique(explode(',', $tags)));
        $tags = explode(',', $tags);
        $home_content['top_stories']['tags'] = $tags;


        

        @$categories         = Category::where('is_featured',1)->where('language', $language)->get();

        foreach (@$categories as $category):
            $home_content[$category->category_name]['cat_type']         = $category->category_name;
            $home_content[$category->category_name]['cat_title']        = $category->category_name;
            $home_content[$category->category_name]['sliders']          = $this->imageUrlset($this->commentsCount($this->dateToHuman($this->getFeaturedPosts($category->id, $language, 5))));
            $home_content[$category->category_name]['articles']         = $this->imageUrlset($this->commentsCount($this->dateToHuman($this->getFeaturedPosts($category->id, $language, 10,5))));
            $home_content[$category->category_name]['trending_posts']   = $this->trendingPosts($language, $category->id);
            $tags = '';

            foreach($home_content[$category->category_name]['sliders'] as $post):
                $tags .= ($tags == '' ?'': ',').$post->tags;
            endforeach;

            foreach($home_content[$category->category_name]['articles'] as $post):
                $tags .= ($tags == '' ?'': ',').$post->tags;
            endforeach;

            foreach($home_content[$category->category_name]['trending_posts'] as $post):
                $tags .= ($tags == '' ?'': ',').$post->tags;
            endforeach;
            $tags = implode(',', array_unique(explode(',', $tags)));
            $tags = explode(',', $tags);
            $home_content[$category->category_name]['tags'] = $tags;
        endforeach;
        $data = [];

        foreach ($home_content as $content):
            $data[] = $content;
        endforeach;

        return $this->responseWithSuccess(__('successfully_found'),$data , 200);
    }

    private function popularPost($language, $limit= 0, $offset = 0, $category_id = '') {

        return Post::with('image','category:id,category_name,slug','user:id,first_name,last_name')
            ->where('visibility', 1)
            ->where('post_type', 'video')
            ->where('status', 1)
            ->where('language',$language)
            ->when(Sentinel::check() == false, function ($query) {
                $query->where('auth_required', 0);
            })
            ->when($category_id != '', function ($query) use ($category_id){
                $query->where('category_id', $category_id);
            })
            ->select('id','category_id','image_id','user_id','title','slug','post_type','tags','created_at')
            ->orderBy('total_hit','DESC')
            ->offset($offset)
            ->take($limit)
            ->get();
    }

    private function trendingPosts($language, $category_id = ''){

        $hitPosts = VisitorTracker::with('posts:id,category_id,image_id,user_id,title,slug,post_type,language,tags,created_at','posts.image','posts.category:id,category_name,slug','posts.user:id,first_name,last_name')
            ->whereHas('posts', function ($inner_query) use ($language){
                $inner_query->where('post_type','video');
                $inner_query->where('language',$language);
            })
            ->select(DB::raw('count(*) as hitsCount, slug'))->where('page_type',VisitorPageType::PostDetailPage)
            ->when($category_id != '', function ($query) use ($category_id){
                $query->whereHas('posts', function ($inner_query) use ($category_id) {
                    $inner_query->where('category_id',$category_id);
                });
            })
            ->groupBy('slug')->orderBy('hitsCount', 'desc')
            ->where('created_at', '>=',  Carbon::now()->subDay(7))
            ->take(10)
            ->get();

        foreach ($hitPosts as $hitPost):

            if (isset($hitPost['posts']->created_at)):
                $hitPost['posts']->created = Carbon::parse($hitPost['posts']->created_at)->diffForHumans();
            endif;

            if (isset($hitPost['posts']->id)):
                $hitPost['posts']->commentsCount = Comment::where('post_id', $hitPost['posts']->id)->where('comment_id', '=' , null)->count();
            endif;

            if (isset($hitPost['posts']->image)) {
                if ($hitPost['posts']->image->disk == 's3') {
                    $s3Link = "https://s3." . Config::get('filesystems.disks.s3.region') . ".amazonaws.com/" . Config::get('filesystems.disks.s3.bucket') . "/";


                    $hitPost['posts']->image->original_image = $s3Link . $hitPost['posts']->image->original_image;
                    $hitPost['posts']->image->og_image = $s3Link . $hitPost['posts']->image->og_image;
                    $hitPost['posts']->image->big_image = $s3Link . $hitPost['posts']->image->big_image;
                    $hitPost['posts']->image->big_image_two = $s3Link . $hitPost['posts']->image->big_image_two;
                    $hitPost['posts']->image->medium_image = $s3Link . $hitPost['posts']->image->medium_image;
                    $hitPost['posts']->image->medium_image_two = $s3Link . $hitPost['posts']->image->medium_image_two;
                    $hitPost['posts']->image->medium_image_three = $s3Link . $hitPost['posts']->image->medium_image_three;
                    $hitPost['posts']->image->small_image = $s3Link . $hitPost['posts']->image->small_image;
                    $hitPost['posts']->image->thumbnail = $s3Link . $hitPost['posts']->image->thumbnail;

                } else {
                    $hitPost['posts']->image->original_image = static_asset($hitPost['posts']->image->original_image);
                    $hitPost['posts']->image->og_image = static_asset($hitPost['posts']->image->og_image);
                    $hitPost['posts']->image->big_image = static_asset($hitPost['posts']->image->big_image);
                    $hitPost['posts']->image->big_image_two = static_asset($hitPost['posts']->image->big_image_two);
                    $hitPost['posts']->image->medium_image = static_asset($hitPost['posts']->image->medium_image);
                    $hitPost['posts']->image->medium_image_two = static_asset($hitPost['posts']->image->medium_image_two);
                    $hitPost['posts']->image->medium_image_three = static_asset($hitPost['posts']->image->medium_image_three);
                    $hitPost['posts']->image->small_image = static_asset($hitPost['posts']->image->small_image);
                    $hitPost['posts']->image->thumbnail = static_asset($hitPost['posts']->image->thumbnail);
                }

                unset($hitPost['posts']->image->id);
                unset($hitPost['posts']->image->disk);
                unset($hitPost['posts']->image->created_at);
                unset($hitPost['posts']->image->updated_at);
            }

        endforeach;

        $trending_post = array();

        foreach($hitPosts as $key => $hitpost):

            $trending_post[] = $hitpost->posts;

        endforeach;

        return $trending_post;

    }
    private function latestPostsVideo($language, $limit = 0 , $offset = '' , $category_id = ''){

        return Post::with('image','category:id,category_name,slug','user:id,first_name,last_name')
            ->where('visibility', 1)
            ->where('status', 1)
            ->where('language',$language)
            ->where('post_type', 'video')
            ->when(Sentinel::check() == false, function ($query) {
                $query->where('auth_required', 0);
            })
            ->when($category_id != '', function ($query) use ($category_id){
                $query->where('category_id', $category_id);
            })
            ->select('id','category_id','image_id','user_id','title','slug','post_type','tags','created_at')
            ->orderBy('id', 'desc')
            ->offset($offset)
            ->take($limit)
            ->get();
    }

    private function getFeaturedPosts($category, $language, $limit = 0, $offset = 0){
        return Post::with('image','user:id,first_name,last_name')
            ->where('category_id', $category)
            ->where('post_type', 'video')
            ->where('visibility', 1)
            ->where('status', 1)
            ->where('language',$language)
            ->when(Sentinel::check() == false, function ($query) {
                $query->where('auth_required', 0);
            })
            ->select('id','category_id','image_id','user_id','title','slug','post_type','tags','created_at')
            ->orderBy('id', 'desc')
            ->offset($offset)
            ->take($limit)
            ->get();
    }

    public function tags(Request $request)
    {
        $language   = $request->lang ?? settingHelper('default_language');

        $values = \DB::table("posts")
            ->select("posts.*",\DB::raw("GROUP_CONCAT(tags) as tagsname"))
            ->where('status',1)
            ->where('language', $language)
            ->where('visibility',1)
            ->get();

        $data = [];

        foreach ($values as $key =>$value):
            $data['tag'] = $value->tagsname;
            break;
        endforeach;

        $tags = array_filter(array_unique(explode(',', $data['tag'])));

        $data = [];

        foreach ($tags as $tag):
            $data[] = ucfirst($tag);
        endforeach;

        return $this->responseWithSuccess(__('successfully_found'),$data , 200);
    }

    public function trendings(Request $request){
        $language   = $request->lang ?? settingHelper('default_language');

        $page   = $request->page ?? 1;

        $offset = ( $page * 15 ) - 15;
        $limit  = 15;

        $hitPosts = VisitorTracker::with('posts:id,category_id,image_id,user_id,title,slug,post_type,language,tags,created_at','posts.image','posts.category:id,category_name,slug','posts.user:id,first_name,last_name')
            ->whereHas('posts', function ($inner_query) use($language, $offset, $limit) {
                $inner_query->where('visibility', 1)
                            ->where('status', 1)
                            ->where('status', 1)
                            ->where('language',$language);
            })
            ->select(DB::raw('count(*) as hitsCount, slug'))->where('page_type',VisitorPageType::PostDetailPage)
            ->groupBy('slug')->orderBy('hitsCount', 'desc')
            ->where('created_at', '>=',  Carbon::now()->subDay(7))
            ->offset($offset)
            ->take($limit)
            ->get();

        foreach ($hitPosts as $hitPost):

            if (isset($hitPost['posts']->created_at)):
                $hitPost['posts']->created = Carbon::parse($hitPost['posts']->created_at)->diffForHumans();
            endif;

            if (isset($hitPost['posts']->id)):
                $hitPost['posts']->commentsCount = Comment::where('post_id', $hitPost['posts']->id)->where('comment_id', '=' , null)->count();
            endif;

            if (isset($hitPost['posts']->image)) {
                if ($hitPost['posts']->image->disk == 's3') {
                    $s3Link = "https://s3." . Config::get('filesystems.disks.s3.region') . ".amazonaws.com/" . Config::get('filesystems.disks.s3.bucket') . "/";


                    $hitPost['posts']->image->original_image = $s3Link . $hitPost['posts']->image->original_image;
                    $hitPost['posts']->image->og_image = $s3Link . $hitPost['posts']->image->og_image;
                    $hitPost['posts']->image->big_image = $s3Link . $hitPost['posts']->image->big_image;
                    $hitPost['posts']->image->big_image_two = $s3Link . $hitPost['posts']->image->big_image_two;
                    $hitPost['posts']->image->medium_image = $s3Link . $hitPost['posts']->image->medium_image;
                    $hitPost['posts']->image->medium_image_two = $s3Link . $hitPost['posts']->image->medium_image_two;
                    $hitPost['posts']->image->medium_image_three = $s3Link . $hitPost['posts']->image->medium_image_three;
                    $hitPost['posts']->image->small_image = $s3Link . $hitPost['posts']->image->small_image;
                    $hitPost['posts']->image->thumbnail = $s3Link . $hitPost['posts']->image->thumbnail;

                } else {
                    $hitPost['posts']->image->original_image = static_asset($hitPost['posts']->image->original_image);
                    $hitPost['posts']->image->og_image = static_asset($hitPost['posts']->image->og_image);
                    $hitPost['posts']->image->big_image = static_asset($hitPost['posts']->image->big_image);
                    $hitPost['posts']->image->big_image_two = static_asset($hitPost['posts']->image->big_image_two);
                    $hitPost['posts']->image->medium_image = static_asset($hitPost['posts']->image->medium_image);
                    $hitPost['posts']->image->medium_image_two = static_asset($hitPost['posts']->image->medium_image_two);
                    $hitPost['posts']->image->medium_image_three = static_asset($hitPost['posts']->image->medium_image_three);
                    $hitPost['posts']->image->small_image = static_asset($hitPost['posts']->image->small_image);
                    $hitPost['posts']->image->thumbnail = static_asset($hitPost['posts']->image->thumbnail);
                }

                unset($hitPost['posts']->image->id);
                unset($hitPost['posts']->image->disk);
                unset($hitPost['posts']->image->created_at);
                unset($hitPost['posts']->image->updated_at);
            }

        endforeach;

        $trending_post = array();

        foreach($hitPosts as $key => $hitpost):

            $trending_post[] = $hitpost->posts;

        endforeach;

        return $this->responseWithSuccess(__('successfully_found'),$trending_post, 200);

    }
}
