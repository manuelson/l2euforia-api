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
    Route::post('online-users', 'CharactersController@showOnline');
    Route::post('characters-user', 'CharactersController@getCharactersByUser');
    Route::post('characters-user-by-email', 'CharactersController@getCharactersByEmail');
    Route::post('new', 'NewsController@showNewId');
    Route::post('news-user', 'NewsController@showNewsUser');
    Route::post('create-new', 'NewsController@createNew');
    Route::post('update-new', 'NewsController@updateNew');
    Route::post('delete-new', 'NewsController@deleteNew');
    Route::post('contact', 'ContactController@showContact');
    Route::post('create-contact', 'ContactController@createContact');
    Route::post('update-contact', 'ContactController@updateContact');

});