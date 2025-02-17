<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Lang;

class LangMacroServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * function default
         *
         * Get the default language of the locale.
         *
         * @return array
         */
        Lang::macro('default', function (): array {
            $locales = config('app.locales');
            $key = config('app.locale'); // The fallback_locale is system fallback translation
            return [
                'language' => $key,
                'description' => (isset($locales[$key]) ? $locales[$key] : 'Default')
            ];
        });

        /**
         * function locales
         *
         * Get the list of locales.
         *
         * @return array
         */
        Lang::macro('locales', function (): array {
            $locales = config('app.locales');
            $locale = config('app.locale');
            $locales[$locale] = (isset($locales[$locale]) ? $locales[$locale] : 'Default');
            return collect($locales)->map(function ($item, $key) {
                /* Replace */
                return [
                    'language' => $key,
                    'description' => $item
                ];
            })->all();
        });

        /**
         * function currentLocale
         *
         * Get the current locale.
         *
         * @return array
         */
        Lang::macro('currentLocale', function (): array {
            $locales = config('app.locales');
            $key = Lang::getLocale();
            return [
                'language' => $key,
                'description' => (isset($locales[$key]) ? $locales[$key] : 'Default')
            ];
        });

        /**
         * function dict
         *
         * Read the translation for the given page tag.
         *
         * @param string $page
         * @param string $tag
         * @param mixed $default
         * @param array $replace
         * @return mixed
         */
        Lang::macro('dict', function (string $page, string $tag, $default = null, array $replace = []) {
            /* Get the file path */
            $page = strtr($page, ['.' => DIRECTORY_SEPARATOR]);
            if (self::has($page . '.' . $tag)) {
                return self::get($page . '.' . $tag, $replace);
            }
            /* Default */
            if (is_string($default)) {
                return self::makeReplacements($default, $replace);
            } elseif (is_array($default) && count($default) > 0) {
                foreach ($default as $key => $value) {
                    $default[$key] = self::makeReplacements($value, $replace);
                }
            }
            return $default;
        });
    }
}
