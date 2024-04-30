<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Modules\Language\Entities\Language;

class LanguageComposer
{
    public function __construct()
    {

    }

    public function compose(View $view)
    {
        if (Schema::hasTable('settings') && Schema::hasTable('languages') ) {
            $language   = Cache::rememberForever('language', function (){
                return Language::where('code', \App::getLocale() ?? settingHelper('default_language'))->first();
            });
        }else{
            $language = ['en' => ['name' => 'English', 'script' => 'Latn', 'native' => 'English', 'regional' => 'en_GB']];
        }


        $view->with('language', $language);
    }
}
