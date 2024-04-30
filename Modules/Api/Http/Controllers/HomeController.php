<?php

namespace Modules\Api\Http\Controllers;

use App\Enums\VisitorPageType;
use App\Reaction;
use App\Traits\PostAttributeSetTrait;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Config;
use Modules\Post\Entities\Category;
use Modules\Post\Entities\Comment;
use Validator;
use DB;
use Modules\Post\Entities\Post;
use Sentinel;
use Carbon\Carbon;
use URL;
use App\Traits\ApiReturnFormat;
use Modules\Appearance\Entities\ThemeSection;
use App\VisitorTracker;


class HomeController extends Controller
{
    use ApiReturnFormat;
    use PostAttributeSetTrait;
    public function homeContents(Request $request){

        $language   = $request->lang ?? settingHelper('default_language');

        //latest posts
        $home_content['latest_posts']['cat_type']           = 'latest_posts';
        $home_content['latest_posts']['cat_title']          = __('latest_posts');
        $home_content['latest_posts']['sliders']            = $this->imageUrlset($this->commentsCount($this->dateToHuman($this->latestPosts($language,5))));
        $home_content['latest_posts']['articles']           = $this->imageUrlset($this->commentsCount($this->dateToHuman($this->latestPosts($language, 10, 5))));
        $home_content['latest_posts']['trending_posts']     = $this->trendingPosts($language);
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
        $home_content['top_stories']['cat_title']      = __('top_stories');
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

    private function latestPosts($language, $limit = 0 , $offset = '' , $category_id = ''){

        return Post::with('image','category:id,category_name,slug','user:id,first_name,last_name')
            ->where('visibility', 1)
            ->where('status', 1)
            ->where('language',$language)
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

    private function featuredCategoryPost($language){

        $categories = Category::where('is_featured', 1)->select('id','category_name','slug')->get();

        foreach ($categories as $category):

            $response[] =
                array(
                    'id' => $category->id,
                    'category_name' => $category->category_name,
                    'slug' => $category->slug,
                    'posts' => $this->imageUrlset($this->commentsCount($this->dateToHuman($this->getFeaturedPosts($category->id, $language))))
                );
        endforeach;

        if (!isset($response)):
            $response[] = array();
        endif;

        return $response;

    }

    private function getFeaturedPosts($category, $language, $limit = 0, $offset = 0){
       return Post::with('image','user:id,first_name,last_name')
            ->where('category_id', $category)
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

    private function trendingPosts($language, $category_id = ''){

        $hitPosts = VisitorTracker::with('posts:id,category_id,image_id,user_id,title,slug,post_type,language,tags,created_at','posts.image','posts.category:id,category_name,slug','posts.user:id,first_name,last_name')
            ->whereHas('posts', function ($inner_query) use ($language){
                $inner_query->where('language',$language);
            })
            ->select(DB::raw('count(*) as hitsCount, slug'))->where('page_type',VisitorPageType::PostDetailPage)
            ->when($category_id != '', function ($query) use ($category_id, $language){
                $query->whereHas('posts', function ($inner_query) use ($category_id, $language) {
                    $inner_query->where('category_id',$category_id)
                                ->where('visibility', 1)
                                ->where('status', 1);
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

    protected function tags(){
        return VisitorTracker::select(DB::raw('count(*) as tags_count, slug'))->where('page_type',VisitorPageType::PostByTagsPage)
            ->groupBy('slug')->orderBy('tags_count', 'desc')->take(6)
            ->get();
    }
}
