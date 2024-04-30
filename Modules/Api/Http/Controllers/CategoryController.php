<?php

namespace Modules\Api\Http\Controllers;

use App\Traits\ApiReturnFormat;
use App\Traits\PostAttributeSetTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Config;
use Modules\Post\Entities\Category;
use Modules\Post\Entities\Post;
use Sentinel;

class CategoryController extends Controller
{
    use ApiReturnFormat;
    use PostAttributeSetTrait;

    public function discover(Request $request)
    {
        $language   = $request->lang ?? settingHelper('default_language');

        $recommended_categories = Category::whereHas('post', function (Builder $query) use ($language){
                                            $query->where('category_id', '!=', '')
                                                    ->where('language',$language)
                                                    ->where('recommended',1)
                                                    ->where('status', 1)
                                                    ->where('visibility', 1);
                                            })->select('id','category_name','slug')->get();

        foreach ($recommended_categories as $recommended):
            $recommended['image'] =  static_asset('default-image/default-123x83.png');
        endforeach;

        $data['recommended_categories'] = $recommended_categories ?? '';

        $featured_categories = Category::whereHas('post', function (Builder $query) use ($language){
                                        $query->where('category_id', '!=', '')
                                            ->where('language',$language)
                                            ->where('featured',1)
                                            ->where('status', 1)
                                            ->where('visibility', 1);
                                        })->select('id','category_name','slug')->get();

        foreach ($featured_categories as $featured):
            $featured['image'] =  static_asset('default-image/default-123x83.png');
        endforeach;

        $data['featured_categories'] = $featured_categories ?? '';

        $discover_by_categories = Category::with('subCategory:id,sub_category_name,category_id')
                                            ->where('language',$language)
                                            ->get(['id', 'category_name']);

        $data['discover_by_categories'] = $discover_by_categories;

        return $this->responseWithSuccess(__('successfully_found'), $data, 200);
    }

    public function discoverRecommendedPosts(Request $request)
    {
        $language   = $request->lang ?? settingHelper('default_language');

        $page   = $request->page ?? 1;

        $offset = ( $page * 15 ) - 15;
        $limit  = 15;

        $posts = Post::with('image','user:id,first_name,last_name')
                ->where('category_id', $request->category)
                ->where('visibility', 1)
                ->where('status', 1)
                ->where('recommended',1)
                ->where('language',$language)
                ->when(Sentinel::check() == false, function ($query) {
                    $query->where('auth_required', 0);
                })
                ->select('id','category_id','image_id','user_id','title','slug','post_type','tags','created_at')
                ->orderBy('id', 'desc')
                ->offset($offset)
                ->take($limit)
                ->get();

        $posts = $this->imageUrlset($this->commentsCount($this->dateToHuman($posts)));


        return $this->responseWithSuccess(__('successfully_found'), $posts, 200);
    }

    public function discoverFeaturedPosts(Request $request)
    {
        $language   = $request->lang ?? settingHelper('default_language');

        $page   = $request->page ?? 1;

        $offset = ( $page * 15 ) - 15;
        $limit  = 15;

        $posts = Post::with('image','user:id,first_name,last_name')
                ->where('category_id', $request->category)
                ->where('visibility', 1)
                ->where('status', 1)
                ->where('featured',1)
                ->where('language',$language)
                ->when(Sentinel::check() == false, function ($query) {
                    $query->where('auth_required', 0);
                })
                ->select('id','category_id','image_id','user_id','title','slug','post_type','tags','created_at')
                ->orderBy('id', 'desc')
                ->offset($offset)
                ->take($limit)
                ->get();

        $posts = $this->imageUrlset($this->commentsCount($this->dateToHuman($posts)));

        return $this->responseWithSuccess(__('successfully_found'), $posts, 200);
    }
}
