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

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');
Route::get('/product', 'HomeController@product')->name('product');
Route::get('/favorite', 'HomeController@favorite')->name('favorite');
Route::get('/show/{id}', 'HomeController@detail')->name('detail');

Route::get('/update/db',function(){
    sleep(30);
    \Log::info("update at:".date('Y-m-d H:i:s'));
});