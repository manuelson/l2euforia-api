<?php

/** @var \Laravel\Lumen\Routing\Router $router */
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

Route::group([

    'prefix' => 'api'

], function ($router) {
    Route::post('login', 'AuthController@login');
    Route::post('register', 'AuthController@register');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('user-profile', 'AuthController@userProfile');
    Route::post('user-profile-by-email', 'AuthController@userProfileByEmail');
    Route::post('exist-user', 'AuthController@existUser');
    Route::post('fg-change-password', 'AuthController@fgChangePassword');
    Route::post('fg-check-token', 'AuthController@fgCheckToken');
    Route::post('online-users', 'CharactersController@showOnline');
    Route::post('characters-user', 'CharactersController@getCharactersByUser');
    Route::post('searchItems', 'CharactersController@searchItems');
    Route::post('changeSex', 'CharactersController@changeSex');
    Route::post('addNobless', 'CharactersController@addNobless');
    Route::post('getTokens', 'CharactersController@getTokens');
    Route::post('changeNickname', 'CharactersController@changeNickname');
    Route::post('characters-user-by-email', 'CharactersController@getCharactersByEmail');
    Route::post('characters-list', 'CharactersController@getList');
    Route::post('new', 'NewsController@showNewId');
    Route::post('news', 'NewsController@getList');
    Route::post('news-user', 'NewsController@showNewsUser');
    Route::post('create-new', 'NewsController@createNew');
    Route::post('update-new', 'NewsController@updateNew');
    Route::post('delete-new', 'NewsController@deleteNew');
    Route::post('contact', 'ContactController@showContact');
    Route::post('create-contact', 'ContactController@createContact');
    Route::post('update-contact', 'ContactController@updateContact');
    Route::post('items', 'ItemsController@getList');

});
