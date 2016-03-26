<?php

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

$app->get('/', function () use ($app) {
    return $app->version();
});

$api = app('Dingo\Api\Routing\Router');


$api->version('v1', ['middleware' => ['api.throttle'] , 'limit' => 100, 'expires' => 5, 'namespace' => 'App\Http\Controllers\Api\V1'], function ($api) {

    $api->post('auth/login', [
        'as'   => 'auth.login',
        'uses' => 'AuthController@login'
    ]);
    $api->post('auth/signup', [
        'as'   => 'auth.signup',
        'uses' => 'AuthController@signup'
    ]);

    $api->post('auth/token/refresh', [
        'as'   => 'auth.refresh',
        'uses' => 'AuthController@refreshToken'
    ]);
    

    $api->group(['middleware' => 'api.auth'], function ($api) {

        $api->post('auth/refreshToken', [
            'as'   => 'auth.refreshToken',
            'uses' => 'AuthController@refreshToken'
        ]);

        $api->get('user', [
            'as'   => 'user.show',
            'uses' => 'UserController@show'
        ]);

        $api->put('user', [
            'as'   => 'user.update',
            'uses' => 'UserController@update'
        ]);

        $api->post('user/password', [
            'as'   => 'user.password.update',
            'uses' => 'UserController@editPassword'
        ]);
    });
});
