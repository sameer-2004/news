<?php

namespace App\Http\View\Composers;

use Illuminate\Support\Facades\Cache;
use Sentinel;
use Illuminate\View\View;
use Modules\Post\Entities\Post;

class BreakingComposer
{
    public function __construct()
    {

    }

    public function compose(View $view)
    {
        if (Sentinel::check()):
            $breakingNewss           = Cache::rememberForever('breakingNewssAuth',function (){
                                       return Post::select('id', 'slug', 'title','updated_at')->orderBy('id','desc')
                                            ->where('breaking',1)
                                            ->where('visibility', 1)
                                            ->where('status', 1)
                                            ->where('language', \App::getLocale() ?? settingHelper('default_language'))
                                            ->limit(10)->get();
                                      });
        else:
            $breakingNewss           = Cache::rememberForever('breakingNewss', function (){
                return Post::select('id', 'slug', 'title','updated_at')->orderBy('id','desc')
                    ->where('breaking',1)
                    ->where('visibility', 1)
                    ->where('status', 1)
                    ->where('language', \App::getLocale() ?? settingHelper('default_language'))
                    ->when(Sentinel::check()== false, function ($query) {
                        $query->where('auth_required',0);
                    })->limit(10)->get();
            });

        endif;

        $view->with('breakingNewss', $breakingNewss);
    }
}
