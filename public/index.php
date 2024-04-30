<?php

use Illuminate\Http\Request;
if (strpos(php_sapi_name(), 'cli') !== false):

else:
    define("LARAVEL_START_FROM_PUBLIC","YES");
endif;


/**
 * 
 * If Php version is less than 7.3 show a php update notice.
 */
if(phpversion() < 7.3):
    echo '<div style="line-height: 31px; margin: 20px auto; max-width:600px; text-align:center; font-family:arial; padding: 0.75rem 1.25rem; margin-bottom: 1rem; border: 1px solid transparent; border-radius: 0.25rem; color: #0c5460; background-color: #d1ecf1; border-color: #bee5eb;">
    Your current PHP version is '.phpversion().'. Please update your PHP version to 7.3 or above. 
    <a target="_blank" href="https://codecanyon.net/item/onno-laravel-news-magazine-script/29030619#item-description__change-log" style="color: #007bff; text-decoration: none; background-color: transparent;"> Click Here </a>for more Information.
    </div>';
    exit;
endif;

/**
 * Laravel - A PHP Framework For Web Artisans
 *
 * @package  Laravel
 * @author   Taylor Otwell <taylor@laravel.com>
 */

define('LARAVEL_START', microtime(true));

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| our application. We just need to utilize it! We'll simply require it
| into the script here so that we don't have to worry about manual
| loading any of our classes later on. It feels great to relax.
|
*/

require __DIR__.'/../vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Turn On The Lights
|--------------------------------------------------------------------------
|
| We need to illuminate PHP development, so let us turn on the lights.
| This bootstraps the framework and gets it ready for use, then it
| will load up this application so that we can run it and send
| the responses back to the browser and delight our users.
|
*/

$app = require_once __DIR__.'/../bootstrap/app.php';

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Once we have the application, we can handle the incoming request
| through the kernel, and send the associated response back to
| the client's browser allowing them to enjoy the creative
| and wonderful application we have prepared for them.
|
*/

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
);

$response->send();

$kernel->terminate($request, $response);
