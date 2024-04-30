<?php

namespace App\Http\View\Composers;

use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;
use Modules\Social\Entities\SocialMedia;

class SocialComposer
{
    public function __construct()
    {

    }

    public function compose(View $view)
    {
        $socialMedias = Cache::rememberForever('socialMedias' , function (){
                            return SocialMedia::where('status', 1)->get();
                        });

        $view->with('socialMedias', $socialMedias);
    }
}
