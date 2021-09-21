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

Route::get('/', ['as' => 'dashboard', 'uses' => 'HomeController@index'])->middleware('auth');
Auth::routes();

Route::group(['namespace' => 'Auth'], function(){    
   //Login Admin
	Route::get('login', ['middleware' => 'web', 'uses' => 'LoginController@index'])->name('login');
    Route::post('login', ['middleware' => 'web', 'uses' => 'LoginController@onSignedIn'])->name('auth.on-sign-in');

    //Sign Out User
    Route::get('logout', ['middleware' => 'web', 'uses' => 'SignOutController@index'])->name('logout');
 
    //Change Password
    Route::get('change-password', ['middleware' => 'web', 'uses' => 'ChangePasswordController@index'])->name('auth.change-password');
    Route::post('change-password', ['middleware' => 'web', 'uses' => 'ChangePasswordController@save'])->name('auth.change-password.save');

    //Forgot Password
    Route::get('forgot-password', ['middleware' => 'web', 'uses' => 'ForgotPasswordController@index'])->name('auth.forgot-password');
    Route::post('forgot-password', ['middleware' => 'web', 'uses' => 'ForgotPasswordController@save'])->name('auth.forgot-password.save');
});

Route::group(['middleware' => ['auth','web']], function () {
    Route::get('dashboard', ['as' => 'dashboard', 'uses' => 'HomeController@index']);
    Route::post('dashboard/approval-list', ['as' => 'dashboard.list', 'uses' => 'HomeController@getDataApproval'])->middleware('ajax-call');
    Route::get('dashboard/trans-list', ['as' => 'dashboard.trans', 'uses' => 'HomeController@getTrans'])->middleware('ajax-call');
    Route::get('dashboard/approval-by-id', ['as' => 'dashboard.approval-by-id', 'uses' => 'HomeController@getApprovalById'])->middleware('ajax-call');

    Route::get('user', ['as' => 'user', 'uses' => 'UserController@index']);
    Route::get('user/create', ['as' => 'user.create', 'uses' => 'UserController@add']);
    Route::get('user/edit', ['as' => 'user.edit', 'uses' => 'UserController@edit']);
    Route::post('user/crud/{request}', ['as' => 'user.crud', 'uses' => 'UserController@crud'])->middleware('ajax-call');
    Route::post('user/delete', ['as' => 'user.delete', 'uses' => 'UserController@delete'])->middleware('ajax-call');
    Route::get('user/list', ['as' => 'user.list', 'uses' => 'UserController@getData'])->middleware('ajax-call');
    Route::get('user/email', ['as' => 'user.email', 'uses' => 'UserController@Email'])->middleware('ajax-call');

    Route::get('product', ['as' => 'product', 'uses' => 'ProductController@index']);
    Route::get('product/create', ['as' => 'product.create', 'uses' => 'ProductController@add']);
    Route::get('product/edit', ['as' => 'product.edit', 'uses' => 'ProductController@edit']);
    Route::post('product/crud', ['as' => 'product.crud', 'uses' => 'ProductController@crud'])->middleware('ajax-call');
    Route::post('product/delete', ['as' => 'product.delete', 'uses' => 'ProductController@delete'])->middleware('ajax-call');
    Route::get('product/list', ['as' => 'product.list', 'uses' => 'ProductController@getData'])->middleware('ajax-call');
  
    Route::get('promo', ['as' => 'promo', 'uses' => 'PromoController@index']);
    Route::get('promo/create', ['as' => 'promo.create', 'uses' => 'PromoController@add']);
    Route::get('promo/edit', ['as' => 'promo.edit', 'uses' => 'PromoController@edit']);
    Route::post('promo/crud', ['as' => 'promo.crud', 'uses' => 'PromoController@crud'])->middleware('ajax-call');
    Route::post('promo/delete', ['as' => 'promo.delete', 'uses' => 'PromoController@delete'])->middleware('ajax-call');
    Route::get('promo/list', ['as' => 'promo.list', 'uses' => 'PromoController@getData'])->middleware('ajax-call');

    Route::get('whitelist', ['as' => 'whitelist', 'uses' => 'WhitelistController@index']);
    Route::get('whitelist/list', ['as' => 'whitelist.list', 'uses' => 'WhitelistController@getData'])->middleware('ajax-call');
    Route::get('whitelist/create', ['as' => 'whitelist.create', 'uses' => 'WhitelistController@add']);
    Route::get('whitelist/edit', ['as' => 'whitelist.edit', 'uses' => 'WhitelistController@edit']);
    Route::post('whitelist/crud', ['as' => 'whitelist.crud', 'uses' => 'WhitelistController@crud'])->middleware('ajax-call');
    Route::post('whitelist/delete', ['as' => 'whitelist.delete', 'uses' => 'WhitelistController@delete'])->middleware('ajax-call');

    Route::get('slider', ['as' => 'slider', 'uses' => 'SliderController@index']);
    Route::get('slider/list', ['as' => 'slider.list', 'uses' => 'SliderController@getData'])->middleware('ajax-call');
    Route::get('slider/create', ['as' => 'slider.create', 'uses' => 'SliderController@add']);
    Route::get('slider/edit', ['as' => 'slider.edit', 'uses' => 'SliderController@edit']);
    Route::post('slider/crud', ['as' => 'slider.crud', 'uses' => 'SliderController@crud'])->middleware('ajax-call');
    Route::post('slider/delete', ['as' => 'slider.delete', 'uses' => 'SliderController@delete'])->middleware('ajax-call');
   
    Route::get('delivery', ['as' => 'delivery', 'uses' => 'DeliveryController@index']);
    Route::get('delivery/list', ['as' => 'delivery.list', 'uses' => 'DeliveryController@getData'])->middleware('ajax-call');
    Route::get('delivery/create', ['as' => 'delivery.create', 'uses' => 'DeliveryController@add']);
    Route::get('delivery/edit', ['as' => 'delivery.edit', 'uses' => 'DeliveryController@edit']);
    Route::post('delivery/crud', ['as' => 'delivery.crud', 'uses' => 'DeliveryController@crud'])->middleware('ajax-call');
    Route::post('delivery/delete', ['as' => 'delivery.delete', 'uses' => 'DeliveryController@delete'])->middleware('ajax-call');

    Route::get('order', ['as' => 'order', 'uses' => 'OrderController@index']);
    Route::get('order/detail', ['as' => 'order.detail', 'uses' => 'OrderController@detail']);
    Route::post('order/list', ['as' => 'order.list', 'uses' => 'OrderController@getData'])->middleware('ajax-call');
    Route::get('order/get', ['as' => 'order.getOrder', 'uses' => 'OrderController@getByNo'])->middleware('ajax-call');
    Route::post('order/update-payment', ['as' => 'order.updatePayment', 'uses' => 'OrderController@updatePayment'])->middleware('ajax-call');
    Route::post('order/update-kirim', ['as' => 'order.updateKirim', 'uses' => 'OrderController@updateKirim'])->middleware('ajax-call');

    Route::get('report', ['as' => 'report', 'uses' => 'ReportController@index']);
    Route::post('report/list', ['as' => 'report.list', 'uses' => 'ReportController@getData'])->middleware('ajax-call');
    Route::get('report/list', ['as' => 'report.list', 'uses' => 'ReportController@getData'])->middleware('ajax-call');
    Route::get('report/dropdown', ['as' => 'report.dropdown', 'uses' => 'ReportController@getDropdown'])->middleware('ajax-call');

    Route::get('log', ['as' => 'log', 'uses' => 'LogsController@index']);
    Route::get('log/list', ['as' => 'log.list', 'uses' => 'LogsController@getData'])->middleware('ajax-call');
});