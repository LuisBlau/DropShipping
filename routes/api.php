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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('resetuserpassword', [
    'uses' => 'App\Http\Controllers\Auth\ResetPasswordController@forgotpassword',
    'as' => 'api.auth.forgotpassword'
]);

/*******************************************************************
*              This is the Web API Group
*******************************************************************/
$router->group(['prefix' => 'api-v1'], function() use ($router)
{
    /*******************************************************************
    *              Auth Module
    *******************************************************************/


    Route::post('setuserpassword', [
        'uses' => 'App\Http\Controllers\Auth\ResetPasswordController@setpassword',
        'as' => 'api.auth.setpassword'
    ]);

    /*******************************************************************
    *              Dashboard
    *******************************************************************/
    Route::post('dashboardsmshourly', [
        'uses' => 'App\Http\Controllers\Administration\DashboardController@dashboardsmshourly',
        'as' => 'api.dashboard.smshourly'
    ]);
    Route::post('dashboardsmsdaily', [
        'uses' => 'App\Http\Controllers\Administration\DashboardController@dashboardsmsdaily',
        'as' => 'api.dashboard.smsdaily'
    ]);

    /*******************************************************************
    *              Administration
    *******************************************************************/

    //---------Beautyfort-----------
    Route::get('beautyfortallproducts', [
        'uses' => 'App\Http\Controllers\Administration\BeautyfortController@allproducts',
        'as' => 'api.beautyfort.allproducts'
    ]);
    Route::get('beautyfortnewstocks', [
        'uses' => 'App\Http\Controllers\Administration\BeautyfortController@newstocks',
        'as' => 'api.beautyfort.newstocks'
    ]);
    Route::get('beautyfortremovedstocks', [
        'uses' => 'App\Http\Controllers\Administration\BeautyfortController@removedstocks',
        'as' => 'api.beautyfort.removedstocks'
    ]);
    Route::get('beautyfortavailablestocks', [
        'uses' => 'App\Http\Controllers\Administration\BeautyfortController@availablestocks',
        'as' => 'api.beautyfort.availablestocks'
    ]);

    //---------Ebay-----------
    Route::get('ebayallproducts', [
        'uses' => 'App\Http\Controllers\Administration\EbayController@allproducts',
        'as' => 'api.ebay.allproducts'
    ]);

    //---------Shopify-----------
    Route::get('shopifyproducts', [
        'uses' => 'App\Http\Controllers\Administration\ShopifyController@getProducts',
        'as' => 'api.shopify.products'
    ]);
    Route::get('shopifyremovedproducts', [
        'uses' => 'App\Http\Controllers\Administration\ShopifyController@getRemovedProducts',
        'as' => 'api.shopify.removedProducts'
    ]);
    Route::get('shopifyuploadedproducts', [
        'uses' => 'App\Http\Controllers\Administration\ShopifyController@getUploadedProducts',
        'as' => 'api.shopify.uploadedProducts'
    ]);

    //---------User Management-----------
    Route::get('manageusersdata', [
        'uses' => 'App\Http\Controllers\Administration\UsermanagementController@usersdata',
        'as' => 'api.users.getlist'
    ]);
    Route::get('userloginhistory', [
        'uses' => 'App\Http\Controllers\Administration\UsermanagementController@userloginhistory',
        'as' => 'api.users.getuserloginhistory'
    ]);
    Route::post('adduser', [
        'uses' => 'App\Http\Controllers\Administration\UsermanagementController@add',
        'as' => 'api.users.add'
    ]);
    Route::post('addcustomer', [
        'uses' => 'App\Http\Controllers\Administration\UsermanagementController@addnormaluser',
        'as' => 'api.customers.add'
    ]);
    Route::post('getcustomerdomains', [
        'uses' => 'App\Http\Controllers\Administration\UsermanagementController@getcustomerdomains',
        'as' => 'api.customers.getcustomerdomains'
    ]);
    Route::post('updatecustomerdomains', [
        'uses' => 'App\Http\Controllers\Administration\UsermanagementController@updatedomains',
        'as' => 'api.customers.updatedomains'
    ]);

    Route::post('resetuserpassword', [
        'uses' => 'App\Http\Controllers\Administration\UsermanagementController@resetpassword',
        'as' => 'api.users.resetpassword'
    ]);
    Route::post('sendnewpassword', [
        'uses' => 'App\Http\Controllers\Auth\ResetPasswordController@sendnewpassword',
        'as' => 'api.users.sendnewpassword'
    ]);

    /*******************************************************************
    *              APIs
    *******************************************************************/

});
