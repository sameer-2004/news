<?php


use App\Helpers\LaravelLocalization;
use Illuminate\Support\Facades\Session;


if (!function_exists('getlocale')) {

    /**
     * add prefix localizaion for routes
     *
     * @param
     * @return
     */
    function getLocale($lang = null)
    {
        $locale = new LaravelLocalization();
       return $locale->setlocale($lang);

    }

    function getLocalizedUrl($locale = null, $url = null){
        $locale = new LaravelLocalization();
        return $locale->getLocalizedURL($locale, $url);
    }
}
