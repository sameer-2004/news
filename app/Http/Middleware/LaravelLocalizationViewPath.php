<?php

namespace App\Http\Middleware;

use Closure;
use App\Helpers\LaravelLocalization;
use Illuminate\Support\Facades\View;
use App\Http\Middleware\LaravelLocalizationMiddlewareBase;

class LaravelLocalizationViewPath extends LaravelLocalizationMiddlewareBase 
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next) {

        //If the URL of the request is in exceptions.
        if ($this->shouldIgnore($request)) {
            return $next($request);
        }
        $localization = new LaravelLocalization();

        $app = app();
        
        $currentLocale = $localization->getCurrentLocale();
        $viewPath = resource_path('views/' . $currentLocale);
        
        // Add current locale-code to view-paths
        View::addLocation($viewPath);

        return $next($request);
    }

}
