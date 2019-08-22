<?php

// 在之前的路由后面配上中间件
// Route::get('/', 'PagesController@root')->name('root')->middleware('verified');
Route::get('/', 'PagesController@root')->name('root');
// 在之前的路由里加上一个 verify 参数
Auth::routes(['verify' => true]);

Route::get('/home', 'HomeController@index')->name('home');

// middleware 中间件 auth 中间件代表需要登录，verified中间件代表需要经过邮箱验证
Route::group(['middleware' => ['auth', 'verified']], function() {
    Route::get('user_information', 'UserController@index')->name('user_information.index');
    Route::put('user_information', 'UserController@update')->name('user_information.update');
    Route::post('user_information', 'UserController@updateAvatar')->name('user_information.updateAvatar');
    Route::get('user_information/change', 'UserController@change')->name('user_information.change');
    Route::put('user_information/change', 'UserController@replace')->name('user_information.replace');
    
    Route::get('user_addresses', 'UserAddressesController@index')->name('user_addresses.index');
    Route::get('user_addresses/create', 'UserAddressesController@create')->name('user_addresses.create');
    Route::post('user_addresses', 'UserAddressesController@store')->name('user_addresses.store');
    Route::get('user_addresses/{user_address}', 'UserAddressesController@edit')->name('user_addresses.edit');
    Route::put('user_addresses/{user_address}', 'UserAddressesController@update')->name('user_addresses.update');
    Route::delete('user_addresses/{user_address}', 'UserAddressesController@destroy')->name('user_addresses.destroy');
    
});