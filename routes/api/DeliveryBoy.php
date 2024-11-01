<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DeliveryBoy\AuthController;
use App\Http\Controllers\Api\DeliveryBoy\HomeController;
use App\Http\Controllers\Api\DeliveryBoy\OrderController;
use App\Http\Controllers\Api\DeliveryBoy\AccountController;
use App\Http\Controllers\Api\DeliveryBoy\CustomerController;
use App\Http\Controllers\Api\DeliveryBoy\ShopController;
use App\Http\Controllers\Api\DeliveryBoy\DeliveryBoyController;

//Delivery boy
Route::prefix('deliveryboy')->namespace('DeliveryBoy')->group(function () {

  //Delivery boy authentication
  Route::post('login', [AuthController::class, 'login']);
  Route::post('forgot', [AuthController::class, 'forgot']);
  Route::get('reset', [AuthController::class, 'token']);
  Route::post('reset', [AuthController::class, 'reset']);

  Route::group(['middleware' => ['auth:delivery_boy-api']], function () {
    // Profile
    Route::get('profile', [AccountController::class, 'profile']);
    Route::post('profile', [AccountController::class, 'updateProfile']);
    Route::get('vendor', [AccountController::class, 'vendor']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('password/update', [AuthController::class, 'updatePassword']);
    Route::get('feedbacks', [OrderController::class, 'showDeliveryBoyFeedbacks']);

      // Orders
    Route::get('orders', [OrderController::class, 'index']);
    Route::get('orders/{order}', [OrderController::class, 'show']);
    Route::post('orders/status/{order}', [OrderController::class, 'updateOrderStatus']);
    Route::post('orders/{order}/markasdelivered', [OrderController::class, 'markAsDelivered']);
    Route::post('orders/{order}/markaspaid', [OrderController::class, 'markAsPaid']);
    Route::post('orders/{order}/sendotp', [OrderController::class, 'sendOtp']);

    // Delivery boy App Version 2.0
    Route::post('assign/{active_order_id}/order', [OrderController::class, 'assignActiveOrder']);

    // This route need to improve
    Route::get('today/orders', [OrderController::class, 'todayOrders']);

    // Contact to Customer
    Route::get('customer/contact/{order_id}', [CustomerController::class, 'contact']);

    // Delivery boy
    Route::get('deliveryboy/status', [DeliveryBoyController::class, 'status']);

    // Shop Info
    Route::get('shop/info', [ShopController::class, 'index']);

    // End version 2.0


    // Other APIs
    Route::get('system_configs', [HomeController::class, 'system_configs']);
  });
});
