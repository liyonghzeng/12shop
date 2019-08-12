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

//Route::get('/', function () {
//
////    echo $cc;
//    return view('welcome');
//});


//Route::get('/home', 'HomeController@index')->name('home');

//展示页面
Route::get('/', 'HomeListController@index');

//详情 展示
Route::get('goodslist', 'HomeListController@goodslist');
//cartlist
Route::get('cartlist', 'HomeListController@cartlist');



Route::get('lCart', 'HomeListController@listCart');

//删除购物车
Route::get('delcart', 'HomeListController@delcart');

//修改购物车数量
Route::get('cartnum', 'HomeListController@cartnum');


//订单 控制器 处理

Route::get('orderindex', 'OrderController@index');
Route::get('solegoods', 'OrderController@solegoods');




Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
