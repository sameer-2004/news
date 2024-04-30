<?php

use Illuminate\Http\Request;
// use Modules\User\Http\Controllers\Api;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('v10')->group(function() {

     Route::post('registers', 'UserController@register');
     Route::post('forgot-password', 'UserController@forgotPassword');
     Route::post('login', 'UserController@authenticate');
     //firebase authentication
     Route::post('firebase-auth', 'UserController@firebaseAuth');

     Route::group(['middleware' => ['jwt.verify','loginCheck','api.localization','CheckApiKey']], function() {

         //UserControler
         Route::prefix('user')->group(function() {

             Route::get('me', 'UserController@getAuthenticatedUser');
             Route::get('logout', 'UserController@logout');
             Route::post('change-password', 'UserController@changePassword');

             Route::post('update-profile', 'UserController@updateUserInfo');

             Route::get('user-details-by-id', 'UserController@userDetailsById');
             Route::get('user-details-by-email', 'UserController@userDetailsByemail');
             Route::post('set-password', 'UserController@setPassword');
             Route::post('deactivate-account', 'UserController@deactivateAccount');
             Route::get('test', 'UserController@test');
         });

         Route::post('save-comment', 'CommentController@save');
         Route::post('save-comment-reply', 'CommentController@saveReply');
     });

    Route::group(['middleware' => ['api.localization', 'CheckApiKey']], function() {
        //HomeController
        Route::prefix('home')->group(function() {
            Route::get('/content', 'HomeController@homeContent');

        });
        //SettingsController
        Route::get('/settings', 'SettingsController@settings');

        //ConfigController
        Route::get('/config', 'ConfigController@config');

        //home page
        Route::get('/home-contents', 'HomeController@homeContents');

        //post by
        Route::get('/latest-posts', 'PostController@latestPosts');
        Route::get('/trending-posts', 'PostController@trendings');
        Route::get('/video-posts', 'PostController@videoPosts');
        Route::get('/video-posts-page', 'PostController@getVideoPosts');
        Route::get('/post-by-category/{id}', 'PostController@postByCategory');
        Route::get('/post-by-sub-category/{id}', 'PostController@postBySubCategory');
        Route::get('/post-by-tag/{slug}', 'PostController@postByTag');
        Route::get('/post-by-author/{id}', 'PostController@postByAuthor');
        Route::get('/post-by-date/{date}', 'PostController@postByDate');
        Route::get('/author-post', 'AuthorController@post');
        Route::get('/author-profile', 'AuthorController@profile');

        //post details url
        Route::get('/detail/{id}', 'PostController@articleDetail');
        Route::get('/comments/{id}', 'CommentController@comments');
        Route::get('/replies/{id}', 'CommentController@replies');

        Route::get('/all-tags', 'PostController@tags');
        Route::get('/discover', 'CategoryController@discover');
        Route::get('/discover-recommended-posts', 'CategoryController@discoverRecommendedPosts');
        Route::get('/discover-featured-posts', 'CategoryController@discoverFeaturedPosts');

        //search
        Route::get('/search', 'PostController@searchPost');
    });

});
