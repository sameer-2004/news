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
    Route::prefix('post')->group(function() {
        Route::group(['middleware'=>['loginCheck']],function(){

            Route::group(['middleware' => ['XSS']], function () {

                //post
                Route::get('/', 'PostController@index')->name('post')->middleware('permissionCheck:post_read');

                Route::get('/create/article', 'PostController@createArticle')->name('create-article')->middleware('permissionCheck:post_write');
                Route::get('/create/video', 'PostController@createVideoPost')->name('create-video-post')->middleware('permissionCheck:post_write');
                Route::get('/create/audio', 'PostController@createAudioPost')->name('create-audio-post')->middleware('permissionCheck:post_write');

                //new type of posts routes
                Route::get('/create/trivia-quiz', 'PostController@createTriviaQuiz')->name('create-trivia-quiz')->middleware('permissionCheck:post_write');
                Route::get('/create/personality-quiz', 'PostController@createPersonalityQuiz')->name('create-personality-quiz')->middleware('permissionCheck:post_write');

                Route::get('/add-trivia-quiz-question', 'QuizController@addTriviaQuizQuestion')->name('add-trivia-quiz-question')->middleware('permissionCheck:post_write');
                Route::get('/add-trivia-quiz-question-to-db', 'QuizController@addTriviaQuizQuestionToDB')->name('add-trivia-quiz-question-to-db')->middleware('permissionCheck:post_write');
                Route::get('/add-trivia-quiz-answer', 'QuizController@addTriviaQuizAnswer')->name('add-trivia-quiz-answer')->middleware('permissionCheck:post_write');
                Route::get('/add-trivia-quiz-answer-to-db', 'QuizController@addTriviaQuizAnswerToDB')->name('add-trivia-quiz-answer-to-db')->middleware('permissionCheck:post_write');
                Route::get('/add-trivia-quiz-result', 'QuizController@addTriviaQuizResult')->name('add-trivia-quiz-result')->middleware('permissionCheck:post_write');
                Route::get('/add-trivia-quiz-result-to-db', 'QuizController@addTriviaQuizResultToDB')->name('add-trivia-quiz-result-to-db')->middleware('permissionCheck:post_write');

                Route::get('/edit/{type}/{id}', 'PostController@editPost')->name('edit-post')->middleware('permissionCheck:post_write');

                Route::delete('/remove-post-form', 'PostController@removePostFrom')->name('remove-post-form')->middleware('permissionCheck:post_write');

                Route::post('/add-to', 'PostController@addPostTo')->name('add-to')->middleware('permissionCheck:post_write');
                Route::post('/update/slider-order', 'PostController@updateSliderOrder')->name('update-slider-order')->middleware('permissionCheck:post_write');
                Route::post('/update/featured-order', 'PostController@updateFeaturedOrder')->name('update-featured-order')->middleware('permissionCheck:post_write');
                Route::post('/update/breaking-order', 'PostController@updateBreakingOrder')->name('update-breaking-order')->middleware('permissionCheck:post_write');
                Route::post('/update/recommended-order', 'PostController@updateRecommendedOrder')->name('update-recommended-order')->middleware('permissionCheck:post_write');
                Route::post('/update/editor-picks-order', 'PostController@updateEditorPicksOrder')->name('update-editor-picks-order')->middleware('permissionCheck:post_write');

                Route::get('/slider', 'PostController@slider')->name('slider-posts')->middleware('permissionCheck:post_read');
                Route::get('/featured', 'PostController@featuredPosts')->name('featured-posts')->middleware('permissionCheck:post_read');
                Route::get('/breaking', 'PostController@breakingPosts')->name('breaking-posts')->middleware('permissionCheck:post_read');

                Route::get('/recommended', 'PostController@recommendedPosts')->name('recommended-posts')->middleware('permissionCheck:post_read');

                Route::get('/editor-picks', 'PostController@editorPicksPosts')->name('editor-picks')->middleware('permissionCheck:post_read');

                Route::get('/pending', 'PostController@pendingPosts')->name('pending-posts')->middleware('permissionCheck:post_read');
                Route::get('/submitted', 'PostController@submittedPosts')->name('submitted-posts')->middleware('permissionCheck:post_read');

                Route::get('/submitted', 'PostController@submittedPosts')->name('submitted-posts')->middleware('permissionCheck:post_read');


                Route::post('/categories/fetch', 'PostController@fetchCategory')->name('category-fetch')->middleware('permissionCheck:post_read');

                Route::post('/sub-categories/fetch', 'PostController@fetchSubcategory')->name('subcategory-fetch')->middleware('permissionCheck:post_read');

                //filter
                Route::get('/filter', 'PostController@filterPost')->name('filter-post')->middleware('permissionCheck:post_read');

                //category
                Route::get('/categories', 'CategoryController@categories')->name('categories')->middleware('permissionCheck:category_read');
                Route::post('/categories/add', 'CategoryController@saveNewCategory')->name('save-new-category')->middleware('permissionCheck:category_write');
                Route::get('/categories/edit/{id}', 'CategoryController@editCategory')->name('edit-category')->middleware('permissionCheck:category_write');
                Route::post('/categories/update', 'CategoryController@updateCategory')->name('update-category')->middleware('permissionCheck:category_write');

                //subcategory
                Route::get('/sub-categories', 'CategoryController@subCategories')->name('sub-categories')->middleware('permissionCheck:sub_category_read');
                Route::post('/sub-categories', 'CategoryController@subCategoriesAdd')->name('save-new-sub-category')->middleware('permissionCheck:sub_category_write');
                Route::get('/sub-categories/edit/{id}', 'CategoryController@editSubCategory')->name('edit-sub-category')->middleware('permissionCheck:sub_category_write');
                Route::post('/sub-categories/update', 'CategoryController@updateSubCategory')->name('update-sub-category')->middleware('permissionCheck:sub_category_write');

                //poll
                Route::get('/polls', 'PollController@polls')->name('polls')->middleware('permissionCheck:polls_read');
                Route::get('/poll/create', 'PollController@create')->name('create-poll')->middleware('permissionCheck:polls_write');
                Route::post('/poll/store', 'PollController@store')->name('store-poll')->middleware('permissionCheck:polls_write');
                Route::get('/poll/edit/{id}', 'PollController@edit')->name('poll-edit')->middleware('permissionCheck:polls_write');
                Route::put('/poll/update/{id}', 'PollController@update')->name('update-poll')->middleware('permissionCheck:polls_write');

                //comments
                Route::get('/comments', 'CommentsController@Comments')->name('comments')->middleware('permissionCheck:comments_read');
                Route::get('/comment/setting', 'CommentsController@index')->name('setting-comment')->middleware('permissionCheck:comments_write');
                Route::post('/update/comment-setting', 'CommentsController@updateCommentSettings')->name('update-comment-settings')->middleware('permissionCheck:comments_write');

                Route::get('add-content', 'AddContentController@addContent')->name('add-content')->middleware('permissionCheck:post_write');
                Route::get('btn-image-modal-content/{content_count}', 'AddContentController@btnImageModalContent')->middleware('permissionCheck:post_write');
                Route::get('btn-image-modal-content/{content_count}', 'AddContentController@btnImageModalContent')->middleware('permissionCheck:post_write');

            });

            //post routes
            Route::post('/save/new-post/{type}', 'PostController@saveNewPost')->name('save-new-post')->middleware('permissionCheck:post_write');
            Route::post('/update/{type}/{id}', 'PostController@updatePost')->name('update-post')->middleware('permissionCheck:post_write');

            //quiz routes
            Route::post('/save/new-quiz/{type}', 'QuizController@saveNewQuiz')->name('save-new-quiz')->middleware('permissionCheck:post_write');
            //quiz routes
            Route::post('/update-quiz/{type}/{id}', 'QuizController@updateQuiz')->name('update-quiz')->middleware('permissionCheck:post_write');

//            rss routes
            Route::get('/rss-feeds', 'RssController@index')->name('rss-feeds')->middleware('permissionCheck:rss_read');
            Route::get('/import-rss', 'RssController@importRss')->name('import-rss')->middleware('permissionCheck:rss_write');
            Route::post('/save-rss-feed', 'RssController@saveNewRss')->name('save-rss-feed')->middleware('permissionCheck:rss_write');
            Route::get('/rss-feeds/edit/{id}', 'RssController@editRss')->name('edit-rss')->middleware('permissionCheck:rss_write');
            Route::post('/update-rss-feeds/{id}', 'RssController@updateRss')->name('update-rss')->middleware('permissionCheck:rss_write');

//            filter
            Route::get('/filter-rss', 'RssController@filter')->name('filter-rss')->middleware('permissionCheck:rss_read');

//            feed importing manually
            Route::get('/manually-feeding/{id}', 'RssController@manualImport')->name('manually-feeding')->middleware('permissionCheck:post_write');

        });
    });
});
