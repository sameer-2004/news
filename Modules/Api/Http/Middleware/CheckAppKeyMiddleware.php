<?php

namespace Modules\Api\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Traits\ApiReturnFormat;

class CheckAppKeyMiddleware
{
    use ApiReturnFormat;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if($request->hasHeader('apiKey')):
            if($request->header('apiKey') == settingHelper('api_key_for_app')):
                return $next($request);
            endif;
        endif;

        return $this->responseWithError('Invalid Api Key');
    }
}
