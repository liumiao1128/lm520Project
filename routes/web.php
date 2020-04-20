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

//Route::get('/', function () {
//    return view('welcome');
//});

Route::get('/admincp/getCaptcha', 'Admin\LoginController@getCaptcha');//图片验证码
Route::get('/admincp', function () {return redirect('admincp/login');}); // 默认跳转页
Route::resource('/admincp/login', 'Admin\LoginController'); //登陆页
Route::get('/admincp/logout', 'Admin\LoginController@logout');//登出页
Route::get('/admincp/home', 'Admin\CommonController@home'); //公共首页
Route::get('/admincp/welcome', 'Admin\CommonController@welcome'); //公共右侧页
Route::get('/admincp/message/{message}/{second}/{url_forward}', 'Admin\CommonController@message');

Route::group(['middleware' => 'CheckMiddleware','prefix'=>'admincp'], function () {
    Route::get('/Menu/menuList', 'Admin\MenuController@menuList'); //菜单列表
});








