<?php

namespace Modules\Setting\Database\Seeders;

use Modules\Setting\Entities\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Faker\Factory as Faker;


class SeedSettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('en_US');

        Setting::create(['title' => 'default_language', 'value' => 'en', 'lang' => 'en']);
        Setting::create(['title' => 'timezone', 'value' => 'Asia/Dhaka', 'lang' => 'en']);
        Setting::create(['title' => 'application_name', 'value' => 'ONNO', 'lang' => 'en']);

        //mail
        Setting::create(['title' => 'mail_driver', 'value' => 'smtp', 'lang' => 'en']);
        Setting::create(['title' => 'mail_host', 'value' => '', 'lang' => 'en']);
        Setting::create(['title' => 'sendmail_path', 'value' => '/usr/bin/sendmail -bs', 'lang' => 'en']);
        Setting::create(['title' => 'mail_port', 'value' => '', 'lang' => 'en']);
        Setting::create(['title' => 'mail_address', 'value' => '', 'lang' => 'en']);
        Setting::create(['title' => 'mail_name', 'value' => 'ONNO News and Magazine', 'lang' => 'en']);
        Setting::create(['title' => 'mail_username', 'value' => '', 'lang' => 'en']);
        Setting::create(['title' => 'mail_password', 'value' => '', 'lang' => 'en']);
        Setting::create(['title' => 'mail_encryption', 'value' => 'ssl', 'lang' => 'en']);

        //storage
        Setting::create(['title' => 'default_storage', 'value' => 'local']);
        Setting::create(['title' => 'aws_access_key_id', 'value' => '']);
        Setting::create(['title' => 'aws_secret_access_key', 'value' => '']);
        Setting::create(['title' => 'aws_default_region', 'value' => '']);
        Setting::create(['title' => 'aws_bucket', 'value' => '']);


        Setting::create(['title' => 'logo', 'value' => 'images/logo.png', 'lang' => 'en']);
        Setting::create(['title' => 'favicon', 'value' => 'images/favicon.png', 'lang' => 'en']);
        // Setting::create(['title' => 'ffmpeg', 'value' => '']);

        //notification setting
        Setting::create(['title' => 'onesignal_api_key', 'value' => '', 'lang' => 'en']);
        Setting::create(['title' => 'onesignal_app_id', 'value' => '', 'lang' => 'en']);

        Setting::create(['title' => 'onesignal_action_message', 'value' => 'We\'d like to show you notifications for the latest updates.', 'lang' => 'en']);
        Setting::create(['title' => 'onesignal_accept_button', 'value' => 'ALLOW', 'lang' => 'en']);
        Setting::create(['title' => 'onesignal_cancel_button', 'value' => 'NO THANKS', 'lang' => 'en']);

        Setting::create(['title' => 'notification_status', 'value' => '0', 'lang' => 'en']);

        //seo setting
        Setting::create(['title' => 'seo_title', 'value' => 'ONNO News and Magazine', 'lang' => 'en']);
        Setting::create(['title' => 'seo_keywords', 'value' => '', 'lang' => 'en']);
        Setting::create(['title' => 'seo_meta_description', 'value' => '', 'lang' => 'en']);
        Setting::create(['title' => 'author_name', 'value' => 'SpaGreen', 'lang' => 'en']);
        Setting::create(['title' => 'og_title', 'value' => 'Your Website Title', 'lang' => 'en']);
        Setting::create(['title' => 'og_description', 'value' => '', 'lang' => 'en']);
        Setting::create(['title' => 'og_image', 'value' => 'images/20201018123322_og_image_49.png', 'lang' => 'en']);
        Setting::create(['title' => 'google_analytics_id', 'value' => 'UA-xxxxxxxx-1', 'lang' => 'en']);

        //dynamic routing prefix
        Setting::create(['title' => 'page_detail_prefix', 'value' => 'page', 'lang' => 'en']);
        Setting::create(['title' => 'article_detail_prefix', 'value' => 'story', 'lang' => 'en']);

        Setting::create(['title' => 'url', 'value' => 'http://127.0.0.1:8000', 'lang' => 'en']);

        //company info
        Setting::create(['title' => 'address', 'value' => 'khilkhet', 'lang' => 'en']);
        Setting::create(['title' => 'email', 'value' => 'info@spagreen.net', 'lang' => 'en']);
        Setting::create(['title' => 'phone', 'value' => '01400620055', 'lang' => 'en']);


        Setting::create(['title' => 'zip_code', 'value' => '1207', 'lang' => 'en']);
        Setting::create(['title' => 'city', 'value' => 'Dhaka', 'lang' => 'en']);
        Setting::create(['title' => 'state', 'value' => 'Dhaka', 'lang' => 'en']);
        Setting::create(['title' => 'country', 'value' => 'Country', 'lang' => 'en']);
        Setting::create(['title' => 'website', 'value' => 'spaGreen.net', 'lang' => 'en']);
        Setting::create(['title' => 'company_registration', 'value' => '123456789', 'lang' => 'en']);
        Setting::create(['title' => 'tax_number', 'value' => '123456789', 'lang' => 'en']);
        Setting::create(['title' => 'about_us_description', 'value' => htmlspecialchars($faker->realText(300)), 'lang' => 'en']);

        //captcha setting
        Setting::create(['title' => 'captcha_secret', 'value' => '', 'lang' => 'en']);
        Setting::create(['title' => 'captcha_sitekey', 'value' => '', 'lang' => 'en']);
        Setting::create(['title' => 'captcha_visibility', 'value' => '0', 'lang' => 'en']);

        Setting::create(['title' => 'copyright_text', 'value' => 'Copyright © 2020 ONNO News and Magazine - All Rights Reserved.', 'lang' => 'en']);
        Setting::create(['title' => 'signature', 'value' => 'Best Regards', 'lang' => 'en']);

        //comment settings
        Setting::create(['title' => 'addthis_public_id', 'value' => '', 'lang' => 'en']);
        Setting::create(['title' => 'addthis_toolbox', 'value' => '', 'lang' => 'en']);
        Setting::create(['title' => 'adthis_option', 'value' => '0', 'lang' => 'en']);
        Setting::create(['title' => 'inbuild_comment', 'value' => '1', 'lang' => 'en']);
        Setting::create(['title' => 'disqus_comment', 'value' => '0', 'lang' => 'en']);
        Setting::create(['title' => 'disqus_short_name', 'value' => '', 'lang' => 'en']);
        Setting::create(['title' => 'facebook_comment', 'value' => '0', 'lang' => 'en']);
        Setting::create(['title' => 'facebook_app_id', 'value' => '', 'lang' => 'en']);

        //predefined head css js
        Setting::create(['title' => 'predefined_header', 'value' => '', 'lang' => 'en']);
        Setting::create(['title' => 'custom_header_style', 'value' => '', 'lang' => 'en']);
        Setting::create(['title' => 'custom_footer_js', 'value' => '', 'lang' => 'en']);

        //ffmpeg setting
        Setting::create(['title' => 'ffmpeg_status', 'value' => '0', 'lang' => 'en']);

        Setting::create(['title' => 'facebook_client_id', 'value' => '', 'lang' => 'en']);
        Setting::create(['title' => 'facebook_client_secretkey', 'value' => '', 'lang' => 'en']);
        Setting::create(['title' => 'facebook_visibility', 'value' => '0', 'lang' => 'en']);
        Setting::create(['title' => 'facebook_callback_url', 'value' => '', 'lang' => 'en']);
        Setting::create(['title' => 'google_client_id', 'value' => '', 'lang' => 'en']);
        Setting::create(['title' => 'google_client_secretkey', 'value' => '', 'lang' => 'en']);
        Setting::create(['title' => 'google_visibility', 'value' => '0', 'lang' => 'en']);
        Setting::create(['title' => 'google_callback_url', 'value' => '', 'lang' => 'en']);
        Setting::create(['title' => 'preloader_option', 'value' => '0', 'lang' => 'en']);
        Setting::create(['title' => 'submit_news_status', 'value' => '1', 'lang' => 'en']);
        Setting::create(['title' => 'version', 'value' => '131', 'lang' => 'en']);

        Model::unguard();
    }
}
