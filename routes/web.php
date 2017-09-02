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
Route::get('/', 'HomeController@index')->name('login');
Route::post('/register', 'Auth\RegisterController@register');
Route::post('/auth/token', 'Auth\TokenController@accessToken');
Route::post('/auth/token/refresh', 'Auth\TokenController@refreshAccessToken');
Route::post('/login', 'Auth\LoginController@login');
Route::post('/logout', 'Auth\LoginController@logout');
Route::post('/password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::post('/password/reset', 'Auth\ResetPasswordController@reset');

Route::middleware(['guest'])->group(function(){
});

Route::middleware(['auth'])->group(function(){
});
Route::middleware(['auth:api'])->group(function(){
	Route::resource('/user/info', 'ProfileController', ['only' => [
			'show', 'update'
	]]);

	Route::post('/user/avatar/{module_id}','AvatarController@store')->name('user.avatar.store');
	Route::get('/user/avatar/{module_id}','AvatarController@show')->name('user.avatar.show');
	Route::put('/user/avatar/{module_id}','AvatarController@update')->name('user.avatar.update');
	Route::delete('/user/avatar/{module_id}','AvatarController@destroy')->name('user.avatar.destroy');

	Route::resource('/user/credit_cards', 'CreditCardController', ['only' => [
		'index','store','show', 'update', 'destroy'
	]]);
	Route::resource('/user/favorites', 'FavoriteController', ['only' => [
			'index', 'store', 'destroy'
		]]);
	Route::resource('/user/orders', 'OrderController', ['only' => [
			'index','show', 'store', 'update','destroy'
		]]);
	Route::resource('/user/products', 'UserProductController', ['only' => [
			'index','show', 'store', 'update','destroy'
		]]);
	Route::resource('/user/laboratories', 'LaboratoryController', ['only' => [
			'index','show', 'store', 'update','destroy'
		]]);

	Route::post('/user/lab_avatar/{module_id}','AvatarController@store')->name('laboratories.avatar.store');
	Route::get('/user/lab_avatar/{module_id}','AvatarController@show')->name('laboratories.avatar.show');
	Route::put('/user/lab_avatar/{module_id}','AvatarController@update')->name('laboratories.avatar.update');
	Route::delete('/user/lab_avatar/{module_id}','AvatarController@destroy')->name('laboratories.avatar.destroy');
});

Route::middleware(['auth:api'])->group(function(){
	Route::resource('/tags', 'TagController', ['only' => [
		'index','store', 'update', 'destroy'
	]]);

	Route::resource('/products', 'ProductController', ['only' => [
		'index','store','show', 'update', 'destroy'
	]]);

	Route::get('/stocks','StockController@index')->name('stock.index');
	Route::resource('/stock', 'StockController', ['only' => [
		'index','store','show', 'update', 'destroy'
	]]);

	Route::post('/product/avatar/{module_id}','AvatarController@store')->name('product.avatar.store');
	Route::get('/product/avatar/{module_id}','AvatarController@show')->name('product.avatar.show');
	Route::put('/product/avatar/{module_id}','AvatarController@update')->name('product.avatar.update');
	Route::delete('/product/avatar/{module_id}','AvatarController@destroy')->name('product.avatar.destroy');

});