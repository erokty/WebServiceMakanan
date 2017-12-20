<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'cors', 'prefix' => '/v1'], function () {
    Route::post('/login', 'UserController@authenticate')->name('user.login');
    Route::post('/register', 'UserController@register')->name('user.register');
    Route::get('/logout/{api_token}', 'UserController@logout')->name('user.logout');

    Route::group(['middleware' => 'auth:api'], function () {
      Route::get('/getCurrentUser', 'UserController@getCurrentUser')->name('user.getCurrentUser');
      Route::get('/getUsers', 'UserController@getUsers')->name('user.getUsers');
      Route::get('/user/destroy/{id}', 'UserController@destroy')->name('user.destroy');
    });

    Route::group(['middleware' => 'auth:api', 'prefix' => '/article'], function () {
      Route::post('/create', 'ArticleController@create')->name('article.create');
      Route::get('/get', 'ArticleController@get')->name('article.get');
      Route::get('/get/{id}', 'ArticleController@get')->name('article.get');
      Route::post('/update/{id}', 'ArticleController@update')->name('article.update');
      Route::get('/destroy/{id}', 'ArticleController@destroy')->name('article.destroy');
      Route::post('/upload/{id}', 'ArticleController@upload')->name('article.upload');
    });

    Route::group(['middleware' => 'auth:api', 'prefix' => '/article/picture'], function () {
      Route::post('/upload/{id}', 'ArticlePictureController@upload')->name('article.picture.upload');
      Route::get('/get/{articleId}', 'ArticlePictureController@get')->name('article.picture.get');
      Route::get('/destroy/{id}', 'ArticlePictureController@destroy')->name('article.picture.destroy');
    });

    Route::group(['middleware' => 'auth:api', 'prefix' => '/grouping'], function () {
      Route::post('/create', 'GroupingController@create')->name('grouping.create');
      Route::get('/get', 'GroupingController@get')->name('grouping.get');
      Route::get('/get/{id}', 'GroupingController@get')->name('grouping.get');
      Route::post('/update/{id}', 'GroupingController@update')->name('grouping.update');
      Route::get('/destroy/{id}', 'GroupingController@destroy')->name('grouping.destroy');
    });

    Route::group(['middleware' => 'auth:api', 'prefix' => '/menu'], function () {
      Route::post('/create', 'MenuController@create')->name('menu.create');
      Route::get('/get', 'MenuController@get')->name('menu.get');
      Route::get('/get/{id}', 'MenuController@get')->name('menu.get');
      Route::post('/update/{id}', 'MenuController@update')->name('menu.update');
      Route::get('/destroy/{id}', 'MenuController@destroy')->name('menu.destroy');
    });

    Route::group(['middleware' => 'auth:api', 'prefix' => '/restaurant'], function () {
      Route::post('/create', 'RestaurantController@create')->name('restaurant.create');
      Route::get('/get', 'RestaurantController@get')->name('restaurant.get');
      Route::get('/get/{id}', 'RestaurantController@get')->name('restaurant.get');
      Route::post('/update/{id}', 'RestaurantController@update')->name('restaurant.update');
      Route::get('/destroy/{id}', 'RestaurantController@destroy')->name('restaurant.destroy');
    });

    Route::group(['middleware' => 'auth:api', 'prefix' => '/review'], function () {
      Route::post('/create', 'ReviewController@create')->name('review.create');
      Route::get('/get', 'ReviewController@get')->name('review.get');
      Route::get('/get/{id}', 'ReviewController@get')->name('review.get');
      Route::post('/update/{id}', 'ReviewController@update')->name('review.update');
      Route::get('/destroy/{id}', 'ReviewController@destroy')->name('review.destroy');
    });

    Route::group(['middleware' => 'cors', 'prefix' => '/file'], function () {
      Route::get('/download/{filename}', 'FileController@download')->name('file.download');
    });

});