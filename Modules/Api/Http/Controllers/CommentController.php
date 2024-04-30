<?php

namespace Modules\Api\Http\Controllers;

use App\Traits\ApiReturnFormat;
use App\Traits\PostAttributeSetTrait;
use Carbon\Carbon;
use Modules\Post\Entities\Post;
use Sentinel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Post\Entities\Comment;

class CommentController extends Controller
{
    use ApiReturnFormat;
    public function save(Request $request)
    {
        $request->validate([
            'comment'       => 'required',
            'post_id'       => 'required',
        ]);

        $data               = $request->except('token');

        $data['user_id']    = Sentinel::getUser()->id;

        $comment            = new Comment();
        $comment->fill($data);
        $comment->save();

        return $this->responseWithSuccess(__('commented'),[] , 200);
    }
    public function saveReply(Request $request)
    {
        $request->validate([
            'comment'       => 'required',
            'post_id'       => 'required',
            'comment_id'    => 'required',
        ]);

        $data               = $request->except('token');
        $data['user_id']    = Sentinel::getUser()->id;

        $comment            = new Comment();

        $comment->fill($data);
        $comment->save();

        return $this->responseWithSuccess(__('replied'),[] , 200);
    }

    public function comments($id)
    {
        $post = Post::with([
                'comments' => function($q){
                    $q->with(['user:id,first_name,last_name,profile_image']);
                    $q->select('id','post_id','user_id','comment_id','comment','status','created_at');
                    $q->where('status',1);
                    $q->where('comment_id',null);
                }
            ])
            ->Where('id', $id)->first();

        if(isset($post['comments'])):
            foreach ($post['comments'] as $comment):

                if (isset($comment->created_at)):
                    $comment->date = Carbon::parse($comment->created_at)->diffForHumans();
                endif;
                unset($comment->created_at);
                $comment['user']['profile_image'] = $comment['user']['profile_image'] ? static_asset($comment['user']['profile_image']) : '' ;
            endforeach;
        endif;

        $data = $post['comments'];

        return $this->responseWithSuccess(__('comments'), $data, 200);

    }

    public function replies($id)
    {
        $comment = Comment::with(['reply' => function ($q)
                    {
                        $q->with('user:id,first_name,last_name,profile_image');
                        $q->select('id', 'post_id', 'user_id', 'comment_id', 'comment', 'status', 'created_at');
                        $q->where('status', 1);
                    }])->Where('id', $id)->first();

        foreach ($comment['reply'] as $reply):
            if (isset($reply->created_at)):
                $reply->date = Carbon::parse($reply->created_at)->diffForHumans();
            endif;
            unset($reply->created_at);
            $reply['user']['profile_image'] = $reply['user']['profile_image'] ? static_asset($reply['user']['profile_image']) : '' ;
        endforeach;

        $data = $comment['reply'];

        return $this->responseWithSuccess(__('comments'), $data, 200);
    }
}
