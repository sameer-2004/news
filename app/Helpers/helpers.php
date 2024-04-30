<?php

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;
use Modules\Setting\Entities\Setting;
use Illuminate\Support\Facades\Config;
use Modules\Appearance\Entities\Theme;
use GeoSot\EnvEditor\Facades\EnvEditor;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Modules\Language\Entities\Language;
use Modules\Appearance\Enums\ThemeVisivilityStatus;

function activeTheme()
{
    $activeTheme = Cache::rememberForever('activeTheme' , function (){
                        return Theme::where('status', ThemeVisivilityStatus::ACTIVE)->first();
                    });
    return $activeTheme;
}

function menuUrl($menu)
{
    if ($menu->source       == 'custom'):

        return $menu->url ?? '#';

    elseif ($menu->source   == 'category') :
        return route('site.category', ['slug' =>  $menu['category'] ? $menu['category']->slug : '#']);

    elseif ($menu->source   == 'sub-category') :
            return route('site.sub-category', ['slug' => $menu['category'] ? $menu['category']->slug : '#']);

    elseif ($menu->source   == 'page') :

        if($menu->page_id == ""):
            return route('image.albums');
        else:
            return route('site.page', ['slug' => $menu['page']->slug]);
        endif;


    elseif ($menu->source   == 'post'):

        return route('article.detail', ['id' => $menu['post']->slug]);

    endif;
}

if (!function_exists('isAppMode')){
    function isAppMode(): bool
    {
        return config('app.mobile_mode') == 'on' || !file_exists(base_path('resources/views/site/layouts/app.blade.php'));
    }
}

if (!function_exists('curlRequest')){
    function curlRequest($url, $fields, $method = 'POST')
    {
        $client = new \GuzzleHttp\Client(['verify' => false]);
        $response = $client->request($method, $url, [
            'form_params' => $fields,
        ]);

        $result = $response->getBody()->getContents();
        return json_decode($result);
    }
}

if (!function_exists('envWrite')) {
    function envWrite($key,$value)
    {
        if (EnvEditor::keyExists($key)) {
            EnvEditor::editKey($key, '"' . trim($value) . '"');
        } else {
            EnvEditor::addKey($key, '"' . trim($value) . '"');
        }
    }
}

if (!function_exists('arrayCheck')){
    function arrayCheck($key,$array): bool
    {
        return is_array($array) && count($array) > 0 && array_key_exists($key,$array) && !empty($array[$key]) && $array[$key] != 'null';
    }
}

if (!function_exists('app_config')){
    function app_config()
    {
        $default_language       = settingHelper('default_language');

        if (!empty($default_language)) :
            Config::set('app.locale', $default_language);
        endif;

        $timezone       = settingHelper('default_time_zone');

        if (!empty($timezone)) :
            date_default_timezone_set($timezone);
        else :
            date_default_timezone_set('Asia/Dhaka');
        endif;

        //supported language setting to laravellocalization
        $languageList              =  Language::with('languageConfig')->where('status',1)->get();
        $supportedLocales          = array();
        if ($languageList->count() > 0) :
            foreach ($languageList as $lang) :
                $langConfigs            = $lang->languageConfig->select('name', 'script', 'native', 'regional')->get();
                foreach ($langConfigs as $langConfig) :
                    $langConfig->flag_icon = $lang->flag;
                    $supportedLocales[$lang->locale] = $langConfig;
                endforeach;
            endforeach;
            Config::set('laravellocalization.supportedLocales', $supportedLocales);
        endif;
    }
}

if (!function_exists('pwa_config')){
    function pwa_config()
    {
        $icon = settingHelper('favicon');
        $short_name = settingHelper('system_short_name') != '' ? settingHelper('system_short_name') : 'Onno';
        $pwa = array(
            'name'          => 'Onno',
            'manifest'      => [
                'name'              => config('app.name'),
                'short_name'        => $short_name,
                'scope'             => '/',
                'start_url'         => '/',
                'background_color'  => '#ffffff',
                'theme_color'       => '#000000',
                'display'           => 'standalone',
                'orientation'       => 'portrait',
                'status_bar'        => 'black',
                'icons'             => [
                    '72x72' => [
                        'path'      => @is_file_exists(@$icon['image_72x72_url']) ? static_asset(@$icon['image_72x72_url']) : static_asset('images/ico/favicon-72x72.png'),
                        'purpose'   => 'any'
                    ],
                    '96x96' => [
                        'path'      => @is_file_exists(@$icon['image_96x96_url']) ? static_asset(@$icon['image_96x96_url']) : static_asset('images/ico/favicon-96x96.png'),
                        'purpose'   => 'any'
                    ],
                    '128x128' => [
                        'path'      => @is_file_exists(@$icon['image_128x128_url']) ? static_asset(@$icon['image_128x128_url']) : static_asset('images/ico/favicon-128x128.png'),
                        'purpose'   => 'any'
                    ],
                    '144x144' => [
                        'path'      => @is_file_exists(@$icon['image_144x144_url']) ? static_asset(@$icon['image_144x144_url']) : static_asset('images/ico/favicon-144x144.png'),
                        'purpose'   => 'maskable any'
                    ],
                    '152x152' => [
                        'path'      => @is_file_exists(@$icon['image_152x152_url']) ? static_asset(@$icon['image_152x152_url']) : static_asset('images/ico/favicon-152x152.png'),
                        'purpose'   => 'any'
                    ],
                    '192x192' => [
                        'path'      => @is_file_exists(@$icon['image_192x192_url']) ? static_asset(@$icon['image_192x192_url']) : static_asset('images/ico/favicon-192x192.png'),
                        'purpose'   => 'any'
                    ],
                    '384x384' => [
                        'path'      => @is_file_exists(@$icon['image_384x384_url']) ? static_asset(@$icon['image_384x384_url']) : static_asset('images/ico/favicon-384x384.png'),
                        'purpose'   => 'any'
                    ],
                    '512x512' => [
                        'path'      => @is_file_exists(@$icon['image_512x512_url']) ? static_asset(@$icon['image_512x512_url']) : static_asset('images/ico/favicon-512x512.png'),
                        'purpose'   => 'any'
                    ],
                ],
                'splash' => [
                    '640x1136'  => @is_file_exists(@$icon['splash_640x1136_url']) ? static_asset(@$icon['splash_640x1136_url']) : static_asset('images/ico/splash-640x1136.png'),
                    '750x1334'  => @is_file_exists(@$icon['splash_750x1334_url']) ? static_asset(@$icon['splash_750x1334_url']) : static_asset('images/ico/splash-750x1334.png'),
                    '828x1792'  => @is_file_exists(@$icon['splash_828x1792_url']) ? static_asset(@$icon['splash_828x1792_url']) : static_asset('images/ico/splash-828x1792.png'),
                    '1125x2436' => @is_file_exists(@$icon['splash_1125x2436_url']) ? static_asset(@$icon['splash_1125x2436_url']) : static_asset('images/ico/splash-1125x2436.png'),
                    '1242x2208' => @is_file_exists(@$icon['splash_1242x2208_url']) ? static_asset(@$icon['splash_1242x2208_url']) : static_asset('images/ico/splash-1242x2208.png'),
                    '1242x2688' => @is_file_exists(@$icon['splash_1242x2688_url']) ? static_asset(@$icon['splash_1242x2688_url']) : static_asset('images/ico/splash-1242x2688.png'),
                    '1536x2048' => @is_file_exists(@$icon['splash_1536x2048_url']) ? static_asset(@$icon['splash_1536x2048_url']) : static_asset('images/ico/splash-1536x2048.png'),
                    '1668x2224' => @is_file_exists(@$icon['splash_1668x2224_url']) ? static_asset(@$icon['splash_1668x2224_url']) : static_asset('images/ico/splash-1668x2224.png'),
                    '1668x2388' => @is_file_exists(@$icon['splash_1668x2388_url']) ? static_asset(@$icon['splash_1668x2388_url']) : static_asset('images/ico/splash-1668x2388png'),
                    '2048x2732' => @is_file_exists(@$icon['splash_2048x2732_url']) ? static_asset(@$icon['splash_2048x2732_url']) : static_asset('images/ico/splash-2048x2732.png'),
                ],@
                'custom' => []
            ]
        );

        Config::set('laravelpwa', $pwa);
    }
}

if (!function_exists('isInstalled')) {

    function isInstalled(): bool
    {
        if (strtolower(config('app.app_installed')) == 'yes') {
            return true;
        }
        return false;
    }
}

if (!function_exists('get_version')):
    function get_version(string $input, int $splitLength = 1)
    {
        $string = '';
        for($i = 0; $i < strlen($input); $i++) {
            $string .= ($i > 0 ? '.' : '').$input[$i];
        }
        return $string;
    }
endif;


if (!function_exists('validate_purchase')){
    function validate_purchase($code, $data)
    {
        $script_url = str_replace("install/process", "", (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");

        $fields = [
            'domain' => urlencode($_SERVER['SERVER_NAME']),
            'version' => isAppMode() ? '103' : '143',
            'item_id' => isAppMode() ? '33255812' : '29030619',
            'url' => urlencode($script_url),
            'purchase_code' => urlencode($code)
        ];

        $curl_response = curlRequest("https://desk.spagreen.net/verify-installation-v2", $fields);

        if (property_exists($curl_response,'status') && $curl_response->status):
            envWrite('DB_HOST', $data['DB_HOST']);
            envWrite('DB_DATABASE', $data['DB_DATABASE']);
            envWrite('DB_USERNAME', $data['DB_USERNAME']);
            envWrite('DB_PASSWORD', $data['DB_PASSWORD']);
            sleep(3);

            $sql        = $curl_response->release_sql_link;
            $zip_file   = $curl_response->release_zip_link;
            if (!is_dir(base_path('public/sql'))):
                try {
                    mkdir(base_path('public/sql'), 0777, true);
                } catch (\Exception $e) {
                    return 'SQL file cannot be Imported. Please check your server permission  or Contact with Script Author.';
                }
            endif;
            if (!is_dir(base_path('public/install'))):
                try {
                    mkdir(base_path('public/install'), 0777, true);
                } catch (\Exception $e) {
                    return 'Zip file cannot be Imported. Please check your server permission  or Contact with Script Author.';
                }
            endif;
            $path = base_path('public/sql/sql.sql');
            try {
                file_put_contents($path, file_get_contents($sql));
            } catch (Exception $e) {
                return $e;
            }

            if ($zip_file)
            {
                try {
                    $file_path = base_path('public/install/installer.zip');
                    file_put_contents($file_path, file_get_contents($zip_file));
                } catch (Exception $e) {
                    return 'Zip file cannot be Imported. Please check your server permission or Contact with Script Author.';
                }
            }
            else{
                return 'Zip file cannot be Imported. Please check your server permission or Contact with Script Author.';
            }

            return 'success';
        else:
            return $curl_response->message;
        endif;
    }
}

if (!function_exists('is_file_exists')) {
    function is_file_exists($item, $storage = 'local')
    {
        if (!blank($item) and !blank($storage)) :
            if ($storage == 'local') :
                if (file_exists('public/' . $item)) :
                    return true;
                endif;
            elseif ($storage == 'aws_s3') :
                if (Storage::disk('s3')->exists($item)) :
                    return true;
                endif;
            elseif ($storage == 'wasabi') :
                if (Storage::disk('wasabi')->exists($item)) :
                    return true;
                endif;
            endif;

        endif;

        return false;
    }
}

if (!function_exists('basePath')) {

    /**
     * description
     *
     * @param
     * @return
     */
    function basePath($image)
    {

        if (!blank($image)):
            if ($image->disk    == 'local') :

                //return public_path();

                if (strpos(php_sapi_name(), 'cli') !== false || defined('LARAVEL_START_FROM_PUBLIC')) {
                    return url('/');
                }else{
                    return url('/public');
                }
            else :
                return "https://s3." . config('filesystems.disks.s3.region') . ".amazonaws.com/" . config('filesystems.disks.s3.bucket');
            endif;
        endif;

    }
}

if (!function_exists('defaultModeCheck')) {

    /**
     * description
     *
     * @param
     * @return
     */
    function defaultModeCheck()
    {
        $mode       = Session::get('mode');
        if ($mode   == "") :
            Session::put('mode', data_get(activeTheme(), 'options.mode'));
        endif;
        return Session::get('mode');

    }
}

if (!function_exists('embedUrl')) {
    /**
     * description
     *
     * @param
     * @return
     */
    function embedUrl($url)
    {
        $url        = str_replace('watch?v=', 'embed/', $url);
        return $url;
    }
}

if (!function_exists('isFileExist')) {

    /**
     * description
     *
     * @param
     * @return
     */
    function isFileExist($item  = '', $file = '')
    {
        if (!blank($item) and !blank($file)) :
            if ($item->disk     == 'local') :
                if (strpos(php_sapi_name(), 'cli') !== false || defined('LARAVEL_START_FROM_PUBLIC')) {
                    $file = $file;
                }else{
                    $file = 'public/'.$file;
                }
                if (File::exists($file)) :
                    return true;
                endif;
             else :
                if (Storage::disk('s3')->exists($file)) :
                    return true;
                endif;
            endif;
        endif;

        return false;
    }
}

if (!function_exists('static_asset')) {

    function static_asset($path = ''){
        if (strpos(php_sapi_name(), 'cli') !== false || defined('LARAVEL_START_FROM_PUBLIC')) {
            return app('url')->asset($path);
        }else{
            return app('url')->asset('public/'.$path);
        }
    }
}

if (!function_exists('safari_check')) {

    function safari_check(){
        if (UserAgentBrowser(\Request()->header('User-Agent')) == 'Apple Safari' || UserAgentBrowser(\Request()->header('User-Agent')) == 'Safari' ||
            UserAgentBrowser(\Request()->header('User-Agent')) == 'Internet Explorer'):
            return true;
        endif;
        return false;
    }

}
if (!function_exists('profile_exist')) {

    function profile_exist($image = ''){
        if (strpos(php_sapi_name(), 'cli') !== false || defined('LARAVEL_START_FROM_PUBLIC')) {
            $file = $image;
        }else{
            $file = 'public/'.$image;
        }
        if (File::exists($file)) :
            return true;
        endif;
    }

}
