<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Modules\Language\Entities\Language;

class WebLocalization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {

        // $availableLang = Language::select('code')->where('status', 'active')->get()->toArray();
        // $availableLang = [];
        // foreach ($availableLang as $key => $value) {
        //     $availableLang[] = array_values($value);
        // }
        // //dd($lang);
        // $lang = App::getLocale();
        // //$prepareLang = in_array($lang, $availableLang) ? $lang : config('app.locale');
        // App::setLocale($lang);
        //$prepareLang = in_array($lang, $availableLang);
        $availableLang = ['en', 'ar'];

        $lang = App::getLocale();
        
        app()->setlocale($lang);
        return $next($request);
    }
}
