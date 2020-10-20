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

Route::get('loginerror', 'UserController@getLoginError');

//Route::post('login', 'UserController@login');
Route::post('login/social', 'UserController@socialLogin');

//Temp Routes till HTTPS.
Route::post('temp-register', 'UserController@register');
Route::post('temp-login', 'UserController@login');


// ITEM CATEGORIES

Route::get('item/type', 'ItemCategoryController@getAllItemTypes');


Route::middleware(['cors', 'auth:api'])->group(function () {

    /* routes for chatting*/
    Route::get('allusers', 'userController@getusers');
    Route::get('getconversations', 'userController@getconversations');
    Route::post('getuserdata', 'userController@getuserdata');
    Route::post('getmessages', 'ChatsController@fetchMessages');
    Route::post('messages', 'ChatsController@sendMessage');
    Route::post('disconnect', 'ChatsController@disConnect');
    Route::post('unreadmessage', 'ChatsController@unreadMessage');
    Route::post('connect', 'ChatsController@connect');
    /* end routes for chatting*/

    Route::get('token/validate', 'UserController@validateToken');

    //Wallet
    Route::get('wallet', 'WalletController@getBalance');
    Route::post('wallet', 'WalletController@addMoney');


    Route::post('user/onboard', 'UserController@onBoard');

    //Offering
    Route::post('offering', 'OfferingController@createOffering');
    Route::post('offering/{offering}/add/images', 'OfferingImageController@addImage');
    Route::get('offering/{offering}', "OfferingController@getOffering");
    Route::get('list/self', 'OfferingController@myOfferings');
    Route::post('offering/{offering}/delete', 'OfferingController@deleteOffering');
    Route::post('offering/{offering}/edit', 'OfferingController@editOffering');

    //Search for nearby offerings.
    Route::post('list', 'ListController@nearbyOffers');

    Route::get('item/category', 'ItemCategoryController@getAllItemCategories');

    //Type hinting.
    Route::post("/item/type-hint", "ItemTypeController@typeCompletion");

    //pickup.
    Route::post("pick-up/item", "PickUpController@createPickUp");
    Route::get("pick-up", "PickUpController@getPickUps");

    //Earning.
    Route::get("earning", "EarningController@getEarnings");


});
