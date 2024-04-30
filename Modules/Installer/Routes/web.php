<?php
use Illuminate\Support\Facades\Route;
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

Route::middleware(['IsNotInstalledCheck', 'XSS'])->group(function () {
    Route::get('install/initialize', 'InstallController@index')->name('install.initialize');
    Route::fallback('InstallController@index');
});
Route::get('install/finalize',  'InstallController@final')->name('install.finalize');
Route::post('install/process', 'InstallController@getInstall')->name('install.process');




