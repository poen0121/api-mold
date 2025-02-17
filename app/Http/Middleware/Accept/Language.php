<?php

namespace App\Http\Middleware\Accept;

use Lang;
use Closure;

class Language
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        /* Accept language */
        $acceptLanguage = $request->header('accept-language');
        if (isset($acceptLanguage)) {
            /* App locales */
            $locales = array_keys(Lang::locales());
            $locales = array_combine($locales, $locales);
            $locales = array_change_key_case($locales, CASE_LOWER);
            /* Base init */
            $language = null;
            $priority = 0.0;
            /* Parse list of comma separated language tags and sort it by the quality value */
            $languageRanges = explode(',', trim($acceptLanguage));
            foreach ($languageRanges as $languageRange) {
                /* Parse match */
                if (preg_match('/(.*);q=([0-1]{0,1}.\d{0,4})/i', trim($languageRange), $match)) {
                    $languageCode = strtolower($match[1]);
                    $weights = floatval($match[2]);
                } else {
                    /*  Priority 1.0 */
                    $languageCode = strtolower(trim($languageRange));
                    $weights = 1.0;
                }
                /*  Calculation priority  */
                if ($weights > $priority && isset($locales[$languageCode])) {
                    $priority = $weights;
                    $language = $locales[$languageCode];
                }
            }
            /* Set locale language code */
            if (isset($language)) {
                Lang::setLocale($language);
            }
        }
        /* Return header language */
        $response = $next($request);
        $response->header('X-Language', Lang::getLocale());
        return $response;
    }
}
