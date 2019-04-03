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
//API

Route::post('/register', 'Auth\RegisterController@register');
Route::post('/registerbyforum', 'Auth\RegisterController@registerbyforum');

Route::post('/auth/token', 'Auth\TokenController@accessToken');
Route::post('/auth/token/refresh', 'Auth\TokenController@refreshAccessToken');
Route::get('/auth/verified','Auth\VerifiedUserController@verified');

Route::get('/auth/facebook/email','Auth\FacebookController@email_exist');

Route::post('/login', 'Auth\LoginController@login');
Route::post('/logout', 'Auth\LoginController@logout');
Route::post('/password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::post('/password/reset', 'Auth\ResetPasswordController@reset');
Route::post('/allpay/feedback','AllpayController@feedback');
Route::post('/ecpay/feedback','EcpayController@feedback');

Route::get('/ecpay/result','EcpayController@result');
Route::post('/ecpay/result','EcpayController@result');

Route::get('/auth/facebook', 'Auth\FacebookController@login');
Route::post('/auth/facebook', 'Auth\FacebookController@login');

Route::post('/auth/google', 'Auth\GoogleController@login');

Route::post('/stocks/products', 'StockModelController@getModelProducts');
Route::middleware(['auth'])->group(function(){

});
Route::middleware(['auth:api'])->group(function(){
	Route::post('/auth/email','Auth\VerifiedUserController@sendVerifyEmail');
	Route::post('/auth/phone','Auth\VerifiedUserController@sendVerificationCode');
	Route::get('/auth/login','Auth\TokenController@isLogin');
});
Route::middleware(['auth:api','verifyUser'])->group(function(){
	Route::put('/password/reset', 'Auth\ResetPasswordController@update');

	Route::get('/user/info','ProfileController@show')->name('info.show');
	Route::put('/user/info','ProfileController@update')->name('info.update');

	Route::post('/user/avatar','AvatarController@store')->name('user.avatar.store');
	Route::get('/user/avatar','AvatarController@show')->name('user.avatar.show');
	Route::put('/user/avatar','AvatarController@update')->name('user.avatar.update');
	Route::delete('/user/avatar','AvatarController@destroy')->name('user.avatar.destroy');

	Route::resource('/user/credit_cards', 'CreditCardController', ['only' => [
		'index','store','show', 'update', 'destroy'
	]]);

	Route::delete('/user/favorites/','FavoriteController@destroy');
	Route::resource('/user/favorites', 'FavoriteController', ['only' => [
			'index', 'store', 'destroy'
		]]);
	Route::put('/user/orders/{order}/payment','OrderController@orderPayment');
	Route::resource('/user/orders', 'OrderController', ['only' => [
			'index','show','store','destroy'
		]]);
	Route::post('/user/orders/trial','OrderController@trial')->name('orders.trial');
	Route::get('/user/invoices/{RelatedNumber}','EcpayController@invoiceQuery')->name('invoices.show');
	
	
	Route::put('/user/products/{product}/install', 'UserProductController@install');
	Route::put('/user/products/{product}/uninstall', 'UserProductController@uninstall');
	Route::put('/user/products/sort', 'UserProductController@sorted');
	Route::resource('/user/products', 'UserProductController', ['only' => [
			'index','show'
		]]);

	Route::delete('/user/laboratories/{laboratory}/products','LaboratoryController@removeProducts')->name('laboratories.product.destroy');
	Route::put('/user/laboratories/{laboratory}/products/sort','LaboratoryController@productSorted')->name('laboratories.product.sort');
	Route::put('/user/laboratories/sort', 'LaboratoryController@sorted');
	Route::resource('/user/laboratories', 'LaboratoryController', ['only' => [
			'index','show', 'store', 'update','destroy'
		]]);
	Route::get('/user/laboratories/product/{pathname}','LaboratoryController@mapping')->name('laboratories.product.mapping');

	Route::post('/user/lab_avatar/{module_id}','AvatarController@store')->name('laboratories.avatar.store');
	Route::get('/user/lab_avatar/{module_id}','AvatarController@show')->name('laboratories.avatar.show');
	Route::put('/user/lab_avatar/{module_id}','AvatarController@update')->name('laboratories.avatar.update');
	Route::delete('/user/lab_avatar/{module_id}','AvatarController@destroy')->name('laboratories.avatar.destroy');

	Route::get('user/promocodes','PromocodeController@getList')->name('user.promocodes.index');
	Route::get('user/promocodes/{promocode}','PromocodeController@show')->name('user.promocodes.show')->where('promocode', '[0-9]+');
	Route::get('user/promocodes/query','PromocodeController@show');

	Route::get('/user/notifications','NotificationController@read')->name('notifications.index');
	Route::get('/user/notifications/unread','NotificationController@unRead')->name('notifications.unRead');
	Route::put('/user/notifications/{notification}','NotificationController@markRead')->name('notifications.update');

	Route::put('/referrers/check','ReferrerController@check');
});

Route::middleware(['web'])->group(function(){

	Route::post('/messages','MessageController@store');

	Route::resource('/tags', 'TagController', ['only' => [
		'index'
	]]);

	Route::get('/products','ProductController@onShelves')->name('products.onShelves');
	Route::get('/products/tags','ProductController@tags')->name('products.tags');
	Route::get('/products/{product}','ProductController@onShelf')->name('products.onShelf')->where('product', '[a-zA-Z0-9_-]+');
	Route::get('/products/avatar/{module_id}','AvatarController@show')->name('product.avatar.show');

	Route::get('/stocks','StockController@lists');

	Route::get('/articles','ArticleController@publishList')->name('articles.publishList');
	Route::get('/articles/{article}','ArticleController@onPublish')->name('articles.onPublish')->where('article', '[0-9]+');

	Route::get('/blogs','Front\ArticleController@index');
	Route::get('/blogs/{slug}','Front\ArticleController@index');
	Route::get('/archives/{slug}','Front\ArticleController@show');

	Route::get('/edms','EdmController@onPublishList');
	Route::get('/edms/{edm}','EdmController@onPublish');

	Route::get('fetch/A0003KLineModel/{stock_code}','DataController@A0003KLineModel');
	Route::get('fetch/ETFKLine/{stock_code}','DataController@ETFKLine');
});


Route::middleware(['client:tag'])->group(function(){
	Route::post('/tags','TagController@store')->name('tags.store');
	Route::put('/tags/{tag}','TagController@update')->name('tags.update');
	Route::delete('/tags/{tag}','TagController@destroy')->name('tags.destroy');
});

Route::middleware(['client:product'])->group(function(){
	Route::get('/products/all','ProductController@index')->name('products.all.index');
	Route::get('/products/all/{product}','ProductController@show')->name('products.all.show');

	Route::post('/products','ProductController@store')->name('products.store');
	Route::put('/products/{product}','ProductController@update')->name('products.update');
	Route::delete('/products/{product}','ProductController@destroy')->name('products.destroy');

	Route::post('/products/avatar/{module_id}','AvatarController@store')->name('product.avatar.store');
	Route::put('/products/avatar/{module_id}','AvatarController@update')->name('product.avatar.update');
	Route::delete('/products/avatar/{module_id}','AvatarController@destroy')->name('product.avatar.destroy');

});

Route::middleware(['client:order'])->group(function(){
	Route::put('/user/orders/{order}','OrderController@update')->name('orders.update');
	Route::put('/user/orders/{order}/cancel','OrderController@cancel')->name('orders.cancel');
});

Route::middleware(['client:user-product'])->group(function(){
	Route::post('/user/products','UserProductController@store')->name('user.products.store');
	Route::put('/user/products/cancel','UserProductController@cancel')->name('user.products.cancel');
	Route::put('/user/products/{product}','UserProductController@update')->name('user.products.update');
	Route::delete('/user/products/{product}','UserProductController@destroy')->name('user.products.destroy');
});

Route::middleware(['client:message'])->group(function(){
	Route::get('/messages/{message}','MessageController@show');
	Route::put('/messages/{message}','MessageController@update');
	Route::delete('/messages/{message}','MessageController@destroy');
});

Route::middleware(['client:company'])->group(function(){
	Route::post('/companies','StockController@store')->name('companies.store');
	Route::put('/companies/{company}','StockController@update')->name('companies.update');
	Route::delete('/companies/{company}','StockController@destroy')->name('companies.destroy');
});
Route::middleware(['client:article'])->group(function(){
	Route::post('/articles','ArticleController@store')->name('articles.store');
	Route::put('/articles/{article}','ArticleController@update')->name('articles.update');
	Route::delete('/articles/{article}','ArticleController@destroy')->name('articles.destroy');
});
Route::middleware(['client:promocode'])->group(function(){
	Route::get('/promocodes','PromocodeController@index')->name('promocodes.index');
	Route::post('/promocodes','PromocodeController@store')->name('promocodes.store');
	Route::put('/promocodes/{promocode}','PromocodeController@update')->name('promocodes.update');
	Route::delete('/promocodes/{promocode}','PromocodeController@destroy')->name('promocodes.destroy');
});
Route::middleware(['client:notificationMessage'])->group(function(){
	Route::get('/notificationMessages','NotificationMessageController@index')->name('notificationMessages.index');
	Route::post('/notificationMessages','NotificationMessageController@store')->name('notificationMessages.store');
	Route::put('/notificationMessages/{notification}','NotificationMessageController@update')->name('notificationMessages.update');
	Route::delete('/notificationMessages/{notification}','NotificationMessageController@destroy')->name('notificationMessages.destroy');
});
Route::middleware(['client:edm'])->group(function(){
	Route::get('/edms/all','EdmController@index')->name('edms.index');
	Route::get('/edms/{edm}/all','EdmController@show')->name('edms.show');
	Route::post('/edms','EdmController@store')->name('edms.store');
	Route::put('/edms/{edm}','EdmController@update')->name('edms.update');
	Route::delete('/edms/{edm}','EdmController@destroy')->name('edms.destroy');
});

Route::middleware(['client:user'])->group(function(){
	Route::put('/users/{user}','UserController@update')->name('users.update');
});

//Admin
//Route::get('/ip', function(){return Request::ip();});
Route::get('/', 'HomeController@home');
Route::post('/api/notifications','NotificationMessageController@send')->name('notificationMessages.send');

Route::group(['middleware' => ['ip','admin'],'prefix' => 'admin'],function(){
	Route::get('/login', 'Admin\Auth\LoginController@loginForm')->name('admin.login');
	Route::post('/login', 'Admin\Auth\LoginController@login');
	Route::get('/', 'HomeController@index')->name('admin.home');
});
Route::group(['middleware' => ['ip','admin','auth:admin','adminToken'],'prefix' => 'admin'],function(){
	Route::post('/logout', 'Admin\Auth\LoginController@logout');

	Route::get('/products/{product}/delete','Admin\ProductController@destroy');
	Route::delete('/products','Admin\ProductController@destroy');
	Route::resource('/products', 'Admin\ProductController', ['except' => [
    	'show'
	]]);

	Route::post('/products/export','Admin\ProductController@export');
	Route::get('/products/{product}','Admin\ProductController@show')->name('products.show')->where('product','[0-9]+');
	Route::get('/products/assigned','Admin\ProductController@assignedView');
	Route::post('/products/assigned','Admin\ProductController@assigned');
	Route::get('/products/sorted','Admin\ProductController@sortedView');
	Route::post('/products/sorted','Admin\ProductController@sorted');
	Route::post('/ckeditor/images','CkeditorImageController@store')->name('ckeditor.image.store');
	
	Route::post('/tags/export','Admin\TagController@export');
	Route::get('/tags/{tag}/delete','Admin\TagController@destroy');
	Route::delete('/tags','Admin\TagController@destroy');
	Route::resource('/tags', 'Admin\TagController');

	Route::post('/companies/export','Admin\CompanyController@export');
	Route::get('/companies/{company}/delete','Admin\CompanyController@destroy');
	Route::delete('/companies','Admin\CompanyController@destroy');
	Route::resource('/companies', 'Admin\CompanyController');


	Route::get('/users/{user}/delete','Admin\UserController@destroy');
	Route::get('/users/{user}/edit','Admin\UserController@edit');
	Route::delete('/users','Admin\UserController@destroy');
	Route::resource('/users', 'Admin\UserController');
	Route::post('/users/export','Admin\UserController@export');

	Route::post('/orders/export','Admin\OrderController@export');
	Route::get('/orders/{order}/delete','Admin\OrderController@destroy');
	Route::get('/orders/{order}/cancel','Admin\OrderController@cancel');
	Route::delete('/orders','Admin\OrderController@destroy');
	Route::resource('/orders', 'Admin\OrderController');

	Route::post('/messages/export','Admin\MessageController@export');
	Route::get('/messages/{message}/delete','Admin\MessageController@destroy');
	Route::delete('/messages','Admin\MessageController@destroy');
	Route::resource('/messages', 'Admin\MessageController');

	Route::post('/articles/export','Admin\ArticleController@export');
	Route::get('/articles/{article}/delete','Admin\ArticleController@destroy');
	Route::delete('/articles','Admin\ArticleController@destroy');
	Route::resource('/articles', 'Admin\ArticleController');

	Route::post('/promocodes/export','Admin\PromocodeController@export');
	Route::get('/promocodes/{promocode}/delete','Admin\PromocodeController@destroy');
	Route::delete('/promocodes','Admin\PromocodeController@destroy');
	Route::resource('/promocodes', 'Admin\PromocodeController', ['except' => [
    	'show'
	]]);
	Route::get('/promocodes/{promocode}','Admin\PromocodeController@show')->name('promocodes.show')->where('promocode','[0-9]+');
	Route::get('/promocodes/import','Admin\PromocodeController@importView');
	Route::post('/promocodes/import','Admin\PromocodeController@import');

	Route::post('/notificationMessages/export','Admin\NotificationMessageController@export');
	Route::get('/notificationMessages/{notificationMessage}/delete','Admin\NotificationMessageController@destroy');
	Route::delete('/notificationMessages','Admin\NotificationMessageController@destroy');
	Route::resource('/notificationMessages', 'Admin\NotificationMessageController');

	Route::post('/edms/export','Admin\EdmController@export');
	Route::get('/edms/{edm}/delete','Admin\EdmController@destroy');
	Route::delete('/edms','Admin\EdmController@destroy');
	Route::resource('/edms', 'Admin\EdmController');

	Route::get('/analysts/{analyst}/delete','Admin\AnalystController@destroy');
	Route::delete('/analysts','Admin\AnalystController@destroy');
	Route::resource('/analysts', 'Admin\AnalystController');

	Route::get('/analysts/{analyst}/grants/{grant}/delete','Admin\AnalystController@grantDestroy');
	Route::delete('/analysts/{analyst}/grants','Admin\AnalystController@grantDestroy');

	Route::get('/analysts/{analyst}/grants', 'Admin\AnalystController@grantList');
	Route::get('/analysts/{analyst}/grants/create', 'Admin\AnalystController@grantCreate');
	Route::get('/analysts/{analyst}/grants/{grant}/edit', 'Admin\AnalystController@grantEdit');
	Route::post('/analysts/{analyst}/grants', 'Admin\AnalystController@grantStore');
	Route::put('/analysts/{analyst}/grants/{grant}', 'Admin\AnalystController@grantUpdate');
	Route::get('/analysts/{analyst}/grants/details', 'Admin\AnalystController@details');

	Route::get('/analysts/{analyst}/grants/amounts', 'Admin\AnalystController@getAmounts');

	Route::get('/referrers/{referrer}/delete','Admin\ReferrerController@destroy');
	Route::delete('/referrers','Admin\ReferrerController@destroy');
	Route::resource('/referrers', 'Admin\ReferrerController');
});
Route::group(['middleware' => ['analyst'],'prefix' => 'analyst'],function(){
	Route::get('/login', 'Analyst\Auth\LoginController@loginForm')->name('analyst.login');
	Route::post('/login', 'Analyst\Auth\LoginController@login');
	Route::get('/logout', 'Analyst\Auth\LoginController@logout');
});
Route::group(['middleware' => ['analyst','auth:analyst'],'prefix' => 'analyst'],function(){
	Route::get('/', 'Analyst\HomeController@index')->name('analyst.home');
	Route::post('/logout', 'Analyst\Auth\LoginController@logout');

	Route::get('/orders', 'Analyst\OrderController@index')->name('analyst.order.index');
	
	Route::get('/grants', 'Analyst\GrantController@index')->name('analyst.grant.index');
	Route::get('/grants/{grant}', 'Analyst\GrantController@show')->name('analyst.grant.show');
	Route::get('/promocodes', 'Analyst\PromocodeController@index')->name('analyst.promocode.index');
});

//Server task
Route::get('/server/flatLaboratoriesProducts','Admin\ServerTaskController@flatLaboratoriesProducts');
Route::get('/server/clearOAuthTokenTable', 'Admin\ServerTaskController@clearOAuthTokenTable');
Route::get('/server/transCompanyIndustries', 'Admin\ServerTaskController@transCompanyIndustries');
Route::get('/server/extendProductExpired', 'Admin\ServerTaskController@extendProductExpired');
Route::get('/server/addProductPlans', 'Admin\ServerTaskController@addProductPlans');
Route::get('/server/addProductToPromocode', 'Admin\ServerTaskController@addProductToPromocode');

Route::get('/server/verifiedFBUser', 'Admin\ServerTaskController@verifiedFBUser');

Route::get('/server/seedUsers', 'Admin\ServerTaskController@seedUsers');
Route::get('/server/destroySeedUsers', 'Admin\ServerTaskController@destroySeedUsers');
