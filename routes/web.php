<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers;
use App\Http\Controllers\Apis\ShopifyCronJobController;

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

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/logout', 'App\Http\Controllers\Auth\LogoutController@index')->name('logout');

Route::get('/', function () {
    if (Auth::check()) {
        return Redirect::to('home');
    } else {
        return view('index');
    }
});

//-------------------User Dashboard----------------------
Route::get('/dashboard', [
    'uses' => 'App\Http\Controllers\WebLinks\DashboardController@pbxdomaindetail',
    //'middleware' => 'roles:dashboardpbxdomaindetail',
    'as' => 'web.dashboard.pbxdomaindetail'
]);
//-------------------Auth----------------------
Route::get('/forgotpassword', function () {
    return view('auth.forgotpassword');
});
Route::get('/password/reset', function () {
    return view('auth.resetpassword');
});
Route::get('/users/email/verification/{token}', [
    'uses' => 'App\Http\Controllers\Auth\ResetPasswordController@emailverification',
    //'middleware' => 'roles:useremailverification',
    'as' => 'web.auth.emailverification'
]);

//------------Beautyfort--------------
Route::get('/allproducts', [
    'uses' => 'App\Http\Controllers\WebLinks\BeautyfortController@getallproducts',
    //'middleware' => 'roles:getallproducts',
    'as' => 'web.beautyfort.getallproducts'
]);
Route::get('/newstocks', [
    'uses' => 'App\Http\Controllers\WebLinks\BeautyfortController@getnewstocks',
    //'middleware' => 'roles:getnewstocks',
    'as' => 'web.beautyfort.getnewstocks'
]);
Route::get('/removedproducts', [
    'uses' => 'App\Http\Controllers\WebLinks\BeautyfortController@getremovedproducts',
    //'middleware' => 'roles:getremovedproducts',
    'as' => 'web.beautyfort.getremovedproducts'
]);
Route::get('/availablestocks', [
    'uses' => 'App\Http\Controllers\WebLinks\BeautyfortController@getavailablestocks',
    //'middleware' => 'roles:getavailablestocks',
    'as' => 'web.beautyfort.getavailablestocks'
]);

//------------Ebay--------------
Route::get('/ebaysellerproducts', [
    'uses' => 'App\Http\Controllers\WebLinks\EbayController@getebaysellerproducts',
    //'middleware' => 'roles:getebaysellerproducts',
    'as' => 'web.ebay.getebaysellerproducts'
]);
Route::get('/ebayuploadeditems', [
    'uses' => 'App\Http\Controllers\WebLinks\EbayController@getebayuploadeditems',
    //'middleware' => 'roles:getebayuploadeditems',
    'as' => 'web.ebay.getebayuploadeditems'
]);
Route::get('/ebayremoveditems', [
    'uses' => 'App\Http\Controllers\WebLinks\EbayController@getebayremoveditems',
    //'middleware' => 'roles:getebayremoveditems',
    'as' => 'web.ebay.getebayremoveditems'
]);

//------------Shopify--------------
Route::get('/shopifyproducts', [
    'uses' => 'App\Http\Controllers\WebLinks\ShopifyController@getAllProducts',
    'as' => 'web.shopify.getAllProducts'
]);
Route::get('/shopifyremoveditems', [
    'uses' => 'App\Http\Controllers\WebLinks\ShopifyController@getRemovedProducts',
    'as' => 'web.shopify.getRemovedProducts'
]);
Route::get('/shopifyuploadeditems', [
    'uses' => 'App\Http\Controllers\WebLinks\ShopifyController@getUploadedProducts',
    'as' => 'web.shopify.getUploadedProducts'
]);

//--------------------User Management---------------------
Route::get('/manageusers', [
    'uses' => 'App\Http\Controllers\WebLinks\UsersController@manageusers',
    //'middleware' => 'roles:manageusers',
    'as' => 'web.users.manage'
]);
Route::get('/userloginhistory', [
    'uses' => 'App\Http\Controllers\WebLinks\UsersController@loginhistory',
    //'middleware' => 'roles:userloginhistory',
    'as' => 'web.users.loginhistory'
]);

//-------------------Cronjob APIs----------------------
Route::get('getbeaufyfort', [
    'uses' => 'App\Http\Controllers\BeaufyfortController@get',
    //'middleware' => 'auth',
    'as' => 'web.beaufyfort.get'
]);
Route::get('filternewoldstocks', [
    'uses' => 'App\Http\Controllers\BeaufyfortController@filternewoldstocks',
    //'middleware' => 'auth',
    'as' => 'web.beaufyfort.filternewoldstocks'
]);
Route::get('addproduct', [
    'uses' => 'App\Http\Controllers\Apis\EbayController@addproduct',
    //'middleware' => 'auth',
    'as' => 'web.ebay.addproduct'
]);
Route::get('getebaysellerproduct', [
    'uses' => 'App\Http\Controllers\Apis\EbayController@sellerproduct',
    //'middleware' => 'auth',
    'as' => 'web.ebay.sellerproduct'
]);

// Route::get('post-products-shopify', [
//     'uses' => 'App\Http\Controllers\Apis\ShopifyCronJobController@postProducts',
//     //'middleware' => 'auth',
//     'as' => 'web.shopify.postProductsCronjob'
// ]);
Route::get('post-products-shopify', [ShopifyCronJobController::class, 'postProducts'])->name('web.shopify.postProductsCronjob');

Route::get('get-products-shopify', [
    'uses' => 'App\Http\Controllers\Apis\ShopifyCronjobController@getProducts',
    //'middleware' => 'auth',
    'as' => 'web.shopify.getProductsCronjob'
]);

Route::get('remove-products-shopify', [
    'uses' => 'App\Http\Controllers\Apis\ShopifyCronjobController@removeProducts',
    //'middleware' => 'auth',
    'as' => 'web.shopify.removeProductsCronjob'
]);

Route::get('remove-test-products-shopify', [
    'uses' => 'App\Http\Controllers\Apis\ShopifyCronjobController@removeAllTestProducts',
    //'middleware' => 'auth',
    'as' => 'web.shopify.removeTestProducts'
]);
