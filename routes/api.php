<?php

use Illuminate\Http\Request;

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

Route::prefix('v1')->group(function () {

  Route::middleware('auth:api')->group(function () {
    //Hotels
    Route::get('hotel/get/{id}', 'v1\HotelController@get');
    Route::post('hotel/edit/{id}', 'v1\HotelController@edit');

    //Rooms
    Route::post('room/edit/{id}', 'v1\RoomController@edit');
    Route::post('room/add', 'v1\RoomController@add');
    Route::post('room/delete/{id}', 'v1\RoomController@delete');
    Route::get('room/get/{id}', 'v1\RoomController@get');
    Route::post('room/getAll', 'v1\RoomController@getAll');

    //RoomTypes
    Route::post('roomtype/edit/{name}', 'v1\RoomTypeController@edit');
    Route::post('roomtype/add', 'v1\RoomTypeController@add');
    Route::post('roomtype/delete/{name}', 'v1\RoomTypeController@delete');
    Route::post('roomtype/get/{name}', 'v1\RoomTypeController@get');
    Route::post('roomtype/getAll', 'v1\RoomTypeController@getAll');


    //Prices
    Route::post('price/edit/{id}', 'v1\PriceController@edit');
    Route::post('price/add', 'v1\PriceController@add');
    Route::post('price/delete/{id}', 'v1\PriceController@delete');
    Route::post('price/get/{id}', 'v1\PriceController@get');
    Route::post('price/getAll', 'v1\PriceController@getAll');


    //Booking
    Route::post('booking/edit/{id}', 'v1\BookingController@edit');
    Route::post('booking/add', 'v1\BookingController@add');
    Route::post('booking/delete/{id}', 'v1\BookingController@delete');
    Route::post('booking/get/{id}', 'v1\BookingController@get');
    Route::post('booking/getAll', 'v1\BookingController@getAll');
  });


  //user auth routes
  Route::prefix('user')->group(function () {
    // Create New User
    Route::post('register', 'v1\UserController@register');
    // Login User
    Route::post('login', 'v1\UserController@login');
    // Refresh the JWT Token
    Route::post('refresh', 'v1\UserController@refresh');
    // Below mention routes are available only for the authenticated users.
    Route::middleware('auth:api')->group(function () {
      // Get user info
      Route::get('info', 'v1\UserController@info');
      // Logout user from application
      Route::post('logout', 'v1\UserController@logout');

    });
  });
});
