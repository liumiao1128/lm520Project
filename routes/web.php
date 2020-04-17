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

Route::get('/admincp/home', 'Admin\CommonController@home');
Route::get('/admincp/welcome', 'Admin\CommonController@welcome');



