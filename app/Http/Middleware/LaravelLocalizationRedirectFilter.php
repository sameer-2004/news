<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;
use App\Helpers\LaravelLocalization;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;
use App\Http\Middleware\LaravelLocalizationMiddlewareBase;

class LaravelLocalizationRedirectFilter extends LaravelLocalizationMiddlewareBase
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //If the URL of the request is in exceptions.
        if ($this->shouldIgnore($request)) {
            return $next($request);
        }
        $localization = new LaravelLocalization();
        $params = explode('/', $request->getPathInfo());

        // Dump the first element (empty string) as getPathInfo() always returns a leading slash
        array_shift($params);

        if (\count($params) > 0) {
            $locale = $params[0];


            if ($localization->checkLocaleInSupportedLocales($locale)) {
                if ($locale == settingHelper('default_language')) {
                    $redirection = $localization->getNonLocalizedURL();

                    // Save any flashed data for redirect
                    app('session')->reflash();

                    return new RedirectResponse($redirection, 302, ['Vary' => 'Accept-Language']);
                }
            }           

        return $next($request);
        }
    }
}
