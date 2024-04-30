<?php

namespace App\Http\View\Composers;

use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;
use Modules\Post\Entities\Post;
use Sentinel;

class LastpostComposer
{
    public function __construct()
    {

    }

    public function compose(View $view)
    {
        if (Sentinel::check()):
            $lastPost    = Cache::rememberForever('lastPost',function (){
                return Post::with('image')->select('id', 'title', 'slug' , 'image_id', 'category_id')
                    ->orderBy('id', 'desc')
                    ->where('visibility', 1)
                    ->where('status', 1)
                    ->where('language', \App::getLocale() ?? settingHelper('default_language'))->first();
            });
        else:
            $lastPost    = Cache::rememberForever('lastPost', function (){
                return Post::with('image')->select('id', 'title', 'slug' , 'image_id', 'category_id')
                    ->orderBy('id', 'desc')
                    ->where('visibility', 1)
                    ->where('status', 1)
                    ->where('language', \App::getLocale() ?? settingHelper('default_language'))
                    ->when(Sentinel::check()== false, function ($query) {
                        $query->where('auth_required',0);
                    })->first();
            });
        endif;

        $view->with('lastPost', $lastPost);
    }
}
