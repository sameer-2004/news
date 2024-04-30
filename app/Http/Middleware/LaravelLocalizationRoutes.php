<?php

namespace App\Http\Middleware;

use Closure;
use App\Http\Middleware\LaravelLocalizationMiddlewareBase;

class LaravelLocalizationRoutes extends LaravelLocalizationMiddlewareBase
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

        $app = app();

        $routeName = $app->getRouteNameFromAPath($request->getUri());

        $app->setRouteName($routeName);

        return $next($request);
    }
}
