<?php

namespace Modules\Installer\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CheckInstalledMiddleware
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
      try {
          DB::connection()->getPdo();
      } catch (\Exception $e) {

          return redirect('install/initialize');
      }

      if (Schema::hasTable('settings') && Schema::hasTable('users') && isInstalled()) {
          return $next($request);
      }

      return redirect('install/initialize');
  }
}
