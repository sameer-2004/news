<?php

namespace App\Http\Middleware;

use App\Helpers\LaravelLocalization;
use Closure;
use Illuminate\Http\RedirectResponse;

class LocaleSessionRedirect extends LaravelLocalizationMiddlewareBase
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

        $params = explode('/', $request->path());
        $locale = session('locale', false);
        $localization = new LaravelLocalization;

        if (\count($params) > 0 && $localization->checkLocaleInSupportedLocales($params[0])) {
            session(['locale' => $params[0]]);

            return $next($request);
        }

        if (empty($locale) && $localization->hideUrlAndAcceptHeader()){
            // When default locale is hidden and accept language header is true,
            // then compute browser language when no session has been set.
            // Once the session has been set, there is no need
            // to negotiate language from browser again.
            $negotiator = new LanguageNegotiator(
                $localization->getDefaultLocale(),
                $localization->getSupportedLocales(),
                $request
            );
            $locale = $negotiator->negotiateLanguage();
            session(['locale' => $locale]);
        }

        if ($locale === false){
            $locale = $localization->getCurrentLocale();
        }

        if (
            $locale &&
            $localization->checkLocaleInSupportedLocales($locale) &&
            !($localization->isHiddenDefault($locale))
        ) {
            app('session')->reflash();
            $redirection = $localization->getLocalizedURL($locale);

            return new RedirectResponse($redirection, 302, ['Vary' => 'Accept-Language']);
        }

        return $next($request);
    }
}
