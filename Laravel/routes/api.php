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

    Route::group(['middleware' => 'auth:api', 'prefix' => '/article'], function () {
      Route::post('/create', 'ArticleController@create')->name('article.create');
      Route::get('/get', 'ArticleController@get')->name('article.get');
      Route::get('/get/{id}', 'ArticleController@get')->name('article.get');
      Route::post('/update/{id}', 'ArticleController@update')->name('article.update');
      Route::get('/destroy/{id}', 'ArticleController@destroy')->name('article.destroy');
    });

    Route::group(['middleware' => 'auth:api', 'prefix' => '/grouping'], function () {
      Route::post('/create', 'GroupingController@create')->name('grouping.create');
      Route::get('/get', 'GroupingController@get')->name('grouping.get');
      Route::get('/get/{id}', 'GroupingController@get')->name('grouping.get');
      Route::post('/update/{id}', 'GroupingController@update')->name('grouping.update');
      Route::get('/destroy/{id}', 'GroupingController@destroy')->name('grouping.destroy');
    });

    Route::group(['middleware' => 'auth:api', 'prefix' => '/restaurant'], function () {
      Route::post('/create', 'RestaurantController@create')->name('restaurant.create');
      Route::get('/get', 'RestaurantController@get')->name('restaurant.get');
      Route::get('/get/{id}', 'RestaurantController@get')->name('restaurant.get');
      Route::post('/update/{id}', 'RestaurantController@update')->name('restaurant.update');
      Route::get('/destroy/{id}', 'RestaurantController@destroy')->name('restaurant.destroy');
    });

    Route::group(['middleware' => 'auth:api', 'prefix' => '/file'], function () {
      Route::get('/download/{filename}', 'FileController@download')->name('file.download');
    });

});