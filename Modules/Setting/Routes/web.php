<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::group(
    [
        'prefix' => getlocale(),
        'middleware' => ['localeSessionRedirect', 'localeViewPath', 'isInstalledCheck', 'localizationRedirect']
    ],
    function () {
        Route::prefix('setting')->group(function () {
            Route::group(['middleware' => ['loginCheck']], function () {


                Route::post('/update/settings', 'SettingController@updateSettings')->name('update-settings')->middleware('permissionCheck:settings_write');

                Route::group(['middleware' => ['XSS']], function () {

                    Route::get('/', 'SettingController@index')->name('setting')->middleware('permissionCheck:settings_read');


    //                adding dropdown links and making update routes
                    Route::get('/setting-general', 'SettingController@generalSetting')->name('setting-general')->middleware('permissionCheck:settings_read');

                    Route::get('/setting-company', 'SettingController@companySetting')->name('setting-company')->middleware('permissionCheck:settings_read');

                    Route::get('/setting-storage', 'SettingController@settingStorage')->name('setting-storage')->middleware('permissionCheck:settings_read');

                   Route::get('/setting-seo', 'SettingController@settingSeo')->name('setting-seo')->middleware('permissionCheck:settings_read');

                    Route::get('/setting-recaptcha', 'SettingController@settingRecaptcha')->name('setting-recaptcha')->middleware('permissionCheck:settings_read');

                    Route::get('/setting-custom-header-footer', 'SettingController@settingCustom')->name('setting-custom-header-footer')->middleware('permissionCheck:settings_read');

    //                email settings
                    Route::get('/email/templates', 'SettingController@emailTemplates')->name('email-templates')->middleware('permissionCheck:settings_read');
                    Route::get('/edit/email-template/{id}', 'SettingController@editEmailTemplates')->name('edit-email-template')->middleware('permissionCheck:settings_write');


                    Route::get('/get-company-information', 'SettingController@getCompanyInfo')->middleware('permissionCheck:settings_read');

                    Route::get('/settings-url', 'SettingController@settingsUrl')->name('settings-url')->middleware('permissionCheck:settings_read');

                    Route::get('/settings-ffmpeg', 'SettingController@settingsFfmpeg')->name('settings-ffmpeg')->middleware('permissionCheck:settings_read');

                    Route::get('/cron-information', 'SettingController@cronInformation')->name('cron-information')->middleware('permissionCheck:settings_read');

                    Route::get('/schedule-run/{slug}', 'SettingController@scheduleRun')->name('schedule-run')->middleware('permissionCheck:settings_read');

                    Route::post('/send/test-mail', 'SettingController@sendTestMail')->name('send-test-mail')->middleware('permissionCheck:settings_write');

                    Route::get('/preferene-control', 'SettingController@prefereneControl')->name('preferene-control')->middleware('permissionCheck:settings_write');

                    Route::get('/setting-social-login', 'SettingController@settingSocialLogin')->name('setting-social-login')->middleware('permissionCheck:settings_write');

                    Route::get('/update-database', 'UpdateController@updateDatabase')->name('update-database')->middleware('permissionCheck:settings_write');
                    Route::post('/update-database', 'UpdateController@updateDatabaseStore')->name('update-database')->middleware('permissionCheck:settings_write');

                    Route::get('/system-update', 'UpdateController@updateSystem')->name('system-update')->middleware('permissionCheck:system_update_read');
                    Route::get('/update-system', 'UpdateController@updateSystemStore')->name('do-system-update')->middleware('permissionCheck:system_update_write');

                    Route::get('/cache', 'SettingController@cacheView')->name('cache')->middleware('permissionCheck:settings_write');
                    Route::post('/cache-update', 'SettingController@cacheUpdate')->name('cache-update')->middleware('permissionCheck:settings_write');

                });
                Route::get('/setting-email', 'SettingController@settingEmail')->name('setting-email')->middleware('permissionCheck:settings_read');
                Route::post('/update/email-template', 'SettingController@updateEmailTemplate')->name('update-email-template')->middleware('permissionCheck:settings_write');
            });
        });
    }
);
