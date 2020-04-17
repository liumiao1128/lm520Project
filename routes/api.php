<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


$api = app('Dingo\Api\Routing\Router');
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
Route::post('/login1', 'Api\v1\LoginController@index');

## TODO:V1版本
$api->version('v1', [
    'namespace' => 'App\Http\Controllers\Api\v1'
], function ($api) {
    //后台需要验证模块的 权限
    $api->group(['prefix' => 'v1'], function ($api) {
        //登陆接口
        $api->post('/login', 'LoginController@index');
    });
});



