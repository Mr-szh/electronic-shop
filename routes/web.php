<?php

// 在之前的路由后面配上中间件
// Route::get('/', 'PagesController@root')->name('root')->middleware('verified');
Route::get('/', 'PagesController@root')->name('root');
// 在之前的路由里加上一个 verify 参数
Auth::routes(['verify' => true]);

Route::get('/home', 'HomeController@index')->name('home');