<?php

namespace Modules\Api\Http\Controllers;

use App\Traits\ApiReturnFormat;
use App\Traits\PostAttributeSetTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Config;
use Modules\Post\Entities\Comment;
use Modules\Post\Entities\Post;
use Modules\User\Entities\User;

class AuthorController extends Controller
{
    use ApiReturnFormat;
    use PostAttributeSetTrait;

    public function profile(Request $request)
    {
        $author = User::where('id',$request->author_id)->get(['id','first_name','last_name','profile_image'])->first();

        $data['id']             = $author['id'];
        $data['name']           = $author['first_name'].' '.$author['last_name'];
        $data['profile_image']  = static_asset($author['profile_image']);

        $posts = Post::where('user_id', $author->id)->get();

        $data['total_comments'] = $this->commentsCount($posts)->sum('commentsCount');
        $data['total_video']    = $posts->where('post_type', 'video')->count();

        return $this->responseWithSuccess(__('successfully_found'), $data, 200);
    }
    public function post(Request $request)
    {
        $language   = $request->lang ?? settingHelper('default_language');

        $page = $request->page ?? 1;

        $offset = ( $page * 15 ) - 15;
        $limit  = 15;

        $posts = Post::with('category:id,category_name,slug','user:id,first_name,last_name,email')
                    ->where('user_id', $request->author_id)
                    ->where('language', $language)
                    ->where('status', 1)
                    ->where('visibility', 1)
                    ->orderByDesc('id')->skip($offset)->take($limit)
                    ->get(['id','category_id','status','sub_category_id','image_id','user_id','title','slug','post_type','tags','created_at']);

        $posts = $this->imageUrlset($this->commentsCount($this->dateToHuman($posts)));
        return $this->responseWithSuccess(__('successfully_found'), $posts, 200);
    }
}
