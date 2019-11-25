<?php

// 在之前的路由后面配上中间件
// Route::get('/', 'PagesController@root')->name('root')->middleware('verified');
Route::get('/', 'PagesController@root')->name('root');
// 在之前的路由里加上一个 verify 参数
Auth::routes(['verify' => true]);

Route::get('/home', 'HomeController@index')->name('home');

Route::get('about', 'PagesController@about')->name('about');
Route::get('faq', 'PagesController@faq')->name('faq');
Route::get('mail', 'PagesController@mail')->name('mail');

// middleware 中间件 auth 中间件代表需要登录，verified中间件代表需要经过邮箱验证
Route::group(['middleware' => ['auth', 'verified']], function() {
    Route::get('user_information', 'UserController@index')->name('user_information.index');
    Route::put('user_information', 'UserController@update')->name('user_information.update');
    Route::post('user _information', 'UserController@updateAvatar')->name('user_information.updateAvatar');
    Route::get('user_information/change', 'UserController@change')->name('user_information.change');
    Route::put('user_information/change', 'UserController@replace')->name('user_information.replace');
    
    Route::get('user_addresses', 'UserAddressesController@index')->name('user_addresses.index');
    Route::get('user_addresses/create', 'UserAddressesController@create')->name('user_addresses.create');
    Route::post('user_addresses', 'UserAddressesController@store')->name('user_addresses.store');
    Route::get('user_addresses/{user_address}', 'UserAddressesController@edit')->name('user_addresses.edit');
    Route::put('user_addresses/{user_address}', 'UserAddressesController@update')->name('user_addresses.update');
    Route::delete('user_addresses/{user_address}', 'UserAddressesController@destroy')->name('user_addresses.destroy');

    Route::post('products/{product}/favorite', 'ProductsController@favor')->name('products.favor');
    Route::delete('products/{product}/favorite', 'ProductsController@disfavor')->name('products.disfavor');

    Route::get('products/favorites', 'ProductsController@favorites')->name('products.favorites');
    Route::delete('products/favorites', 'ProductsController@disfavors')->name('products.disfavors');

    Route::post('cart', 'CartController@add')->name('cart.add');
    Route::get('cart', 'CartController@index')->name('cart.index');
    Route::delete('cart/{sku}', 'CartController@remove')->name('cart.remove');
    
    Route::get('orders', 'OrdersController@index')->name('orders.index');
    Route::post('orders', 'OrdersController@store')->name('orders.store');
    Route::get('orders/{order}', 'OrdersController@show')->name('orders.show');
    
    Route::get('payment/{order}/alipay', 'PaymentController@payByAlipay')->name('payment.alipay');
    Route::get('payment/alipay/return', 'PaymentController@alipayReturn')->name('payment.alipay.return');
    Route::post('orders/{order}/received', 'OrdersController@received')->name('orders.received');
    Route::get('orders/{order}/review', 'OrdersController@review')->name('orders.review.show');
    Route::post('orders/{order}/review', 'OrdersController@sendReview')->name('orders.review.store');
    Route::post('orders/{order}/apply_refund', 'OrdersController@applyRefund')->name('orders.apply_refund');

    Route::get('coupon_codes/{code}', 'CouponCodesController@show')->name('coupon_codes.show');
    Route::get('/users/topics', 'UserController@topicsShow')->name('users.topicsShow');
    Route::post('upload_image', 'TopicsController@uploadImage')->name('topics.upload_image');
    Route::resource('replies', 'RepliesController', ['only' => ['store', 'destroy']]);
    Route::get('atwho', 'RepliesController@atwho')->name('replies.atwho');

    Route::resource('topics', 'TopicsController', ['only' => ['index', 'create', 'store', 'update', 'edit', 'destroy']]);
    Route::resource('notifications', 'NotificationsController', ['only' => ['index']]);

    Route::get('custom', 'ProductsController@custom')->name('custom.index');
    Route::post('config', 'ConfigController@add')->name('config.add');
    Route::delete('config/{sku}', 'ConfigController@remove')->name('config.remove');
    Route::delete('config', 'ConfigController@removeAll')->name('config.removeAll');

    Route::post('crowdfunding_orders', 'OrdersController@crowdfunding')->name('crowdfunding_orders.store');

});

Route::resource('users', 'UserController', ['only' => ['show']]);
Route::resource('topics', 'TopicsController', ['only' => ['index']]);
Route::resource('categories', 'CategoriesController', ['only' => ['show']]);

Route::get('topics/{topic}/{slug?}', 'TopicsController@show')->name('topics.show');

Route::get('products', 'ProductsController@index')->name('products.index');
Route::get('products/{product}', 'ProductsController@show')->name('products.show');

Route::post('payment/alipay/notify', 'PaymentController@alipayNotify')->name('payment.alipay.notify');