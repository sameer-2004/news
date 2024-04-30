<?php

namespace App\Providers;

use Session;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\Setting\Entities\Setting;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Modules\Language\Entities\Language;
use Modules\Language\Entities\LanguageConfig;
use Cartalyst\Sentinel\Native\Facades\Sentinel;
use Illuminate\Support\Facades\Artisan;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        try {

            DB::connection()->getPdo();

          } catch (\Exception $e) {

            $supportedLocales               = ['en' => ['name' => 'English', 'script' => 'Latn', 'native' => 'English', 'regional' => 'en_GB']];

            Config::set('app.locale', 'en');
            Config::set('laravellocalization.supportedLocales', $supportedLocales);

            return redirect('install/initialize');
          }

          if(Schema::hasTable('roles') && settingHelper('version')){
            if(!optional(json_decode(DB::table('roles')->select('permissions')->where('slug','superadmin')->first()->permissions))->system_update_read){
                Artisan::call('migrate', ['--force' => true]);
            }
          }


         if (Schema::hasTable('settings') && Schema::hasTable('languages') ) :

            $default_lang           = Setting::where('title', 'default_language')->first();

            $setting                = Setting::select('title', 'value')->where('lang', @$default_lang->value)->get()->toArray();

            $session_array          = array();

            foreach($setting as $row):
                $session_array[$row['title']] = $row['value'];
            endforeach;

            Config::set('site.settings', $session_array);

            if (!empty($default_lang)) :
                Config::set('app.locale', $default_lang->value);
            else :
                Config::set('app.locale', 'en');
            endif;

            $timezone       = settingHelper('timezone');

            if (!empty($timezone)) :
                date_default_timezone_set($timezone);
            else :
                date_default_timezone_set('America/New_York');
            endif;

            $appName        = settingHelper('application_name');

            if (!empty($appName)) :
                Config::set('app.name', $appName);
            endif;

            $captcha_sitekey        = settingHelper('captcha_sitekey');

            if (!empty($captcha_sitekey)) :
                Config::set('captcha.sitekey', $captcha_sitekey);
            endif;

            $captcha_secret        = settingHelper('captcha_secret');

            if (!empty($captcha_secret)) :
                Config::set('captcha.secret', $captcha_secret);
            endif;

            $default_storage = settingHelper('default_storage');

            //facebook login

            $facebook_client_id        = settingHelper('facebook_client_id');
            if (!empty($facebook_client_id)) :
                Config::set('services.facebook.client_id', $facebook_client_id);
            endif;
            $facebook_client_secret        = settingHelper('facebook_client_secretkey');
            if (!empty($facebook_client_secret)) :
                Config::set('services.facebook.client_secret', $facebook_client_secret);
            endif;
            $facebook_callback_url        = settingHelper('facebook_callback_url');
            if (!empty($facebook_callback_url)) :
                Config::set('services.facebook.redirect', $facebook_callback_url);
            endif;

            $google_client_id        = settingHelper('google_client_id');
            if (!empty($google_client_id)) :
                Config::set('services.google.client_id', $google_client_id);
            endif;
            $google_client_secret        = settingHelper('google_client_secretkey');
            if (!empty($google_client_secret)) :
                Config::set('services.google.client_secret', $google_client_secret);
            endif;
            $google_callback_url        = settingHelper('google_callback_url');
            if (!empty($google_callback_url)) :
                Config::set('services.google.redirect', $google_callback_url);
            endif;

            if(!empty($default_storage)):
                Config::set('filesystems.default', $default_storage);
                // if( $default_storage->value=='s3'):
                    $aws_access_key_id      = settingHelper('aws_access_key_id');
                    $aws_secret_access_key  = settingHelper('aws_secret_access_key');
                    $aws_default_region     = settingHelper('aws_default_region');
                    $aws_bucket             = settingHelper('aws_bucket');
                    $aws_url                ="http://$aws_bucket.s3.$aws_default_region.amazonaws.com";

                    Config::set('filesystems.disks.s3.key', $aws_access_key_id);
                    Config::set('filesystems.disks.s3.secret', $aws_secret_access_key);
                    Config::set('filesystems.disks.s3.region', $aws_default_region);
                    Config::set('filesystems.disks.s3.bucket', $aws_bucket);
                    Config::set('filesystems.disks.s3.url', $aws_url);
                // endif;
            endif;

            if (Schema::hasTable('settings')) {
                $mail_driver = Setting::where('title', 'mail_driver')->first();
                $mail_host = Setting::where('title', 'mail_host')->first();
                $mail_port = Setting::where('title', 'mail_port')->first();
                $mail_address = Setting::where('title', 'mail_address')->first();
                $mail_name = Setting::where('title', 'mail_name')->first();
                $mail_username = Setting::where('title', 'mail_username')->first();
                $mail_password = Setting::where('title', 'mail_password')->first();
                $mail_encryption = Setting::where('title', 'mail_encryption')->first();
                $sendmail_path = Setting::where('title', 'sendmail_path')->first();


                //checking if table is not empty
                if ($mail_driver !=null && $mail_host !=null && $mail_port !=null && $mail_address !=null && $mail_name !=null && $mail_username !=null && $mail_password !=null && $mail_encryption !=null && $sendmail_path != null)
                {
                    $config = array(
                        'driver'     => $mail_driver->value,
                        'host'       => $mail_host->value,
                        'port'       => $mail_port->value,
                        'from' => [
                            'address' => $mail_address->value,
                            'name' => $mail_name->value,
                        ],
                        'encryption' => $mail_encryption->value,
                        'username'   => $mail_username->value,
                        'password'   => $mail_password->value,
                        'sendmail'   => $sendmail_path->value,
                        'pretend'    => false,
                    );
                    Config::set('mail', $config);
                }
            }
        endif;



    }
}
