<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use Modules\Ads\Entities\AdLocation;
use Modules\Language\Entities\Language;
use Illuminate\Support\Facades\Cache;

class ActiveLangComposer
{
    public function __construct()
    {

    }

    public function compose(View $view)
    {
        $activeLang     = Cache::rememberForever('activeLang', function(){
                            return Language::orderBy('name', 'ASC')
                                ->where('status','active')->get();

                            });

        $view->with('activeLang', $activeLang);
    }
}
