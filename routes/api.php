<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => ['verify-token'],'namespace' => 'API'], function() {
    Route::get('product', 'ProductController@index');
    Route::get('product/get-detail', 'ProductController@getById');
    Route::post('product/rating', 'ProductController@rating');

    Route::post('promo/voucher', 'PromoController@submitVoucher');

    Route::get('global-references', 'ParamController@fetch');

    Route::post('order', 'OrderController@order');
    Route::get('order-detail', 'OrderController@getOrderDetail');
    Route::post('order-cancel', 'OrderController@cancelOrder');
    Route::post('order/bukti-trf', 'OrderController@buktiTrf');

});
