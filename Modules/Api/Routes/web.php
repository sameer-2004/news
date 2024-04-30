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
        Route::prefix('api')->group(function () {
            Route::group(['middleware'=>['loginCheck', "XSS"]],function() {

                Route::get('api-settings', 'ApiSettingsController@index')->name('api-settings')->middleware('permissionCheck:api_read');
                Route::get('android-settings', 'ApiSettingsController@androidSettings')->name('android-settings')->middleware('permissionCheck:api_read');
                Route::get('ios-settings', 'ApiSettingsController@iosSettings')->name('ios-settings')->middleware('permissionCheck:api_read');
                Route::get('ads-config', 'ApiSettingsController@adsConfig')->name('ads-config')->middleware('permissionCheck:api_read');
                Route::get('app-config', 'ApiSettingsController@appConfig')->name('app-config')->middleware('permissionCheck:api_read');

                //app intro
                Route::get('app-intro', 'ApiSettingsController@appIntro')->name('app-intro')->middleware('permissionCheck:api_read');
                Route::post('add-intro', 'ApiSettingsController@addIntro')->name('add-intro')->middleware('permissionCheck:api_write');
                Route::get('edit-intro/{id}', 'ApiSettingsController@editIntro')->name('edit-intro')->middleware('permissionCheck:api_write');
                Route::post('update-intro', 'ApiSettingsController@updateIntro')->name('update-intro')->middleware('permissionCheck:api_write');
            });

        });
    });

