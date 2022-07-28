<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;

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

// user endpoints
Route::post('login', [ApiController::class, 'authenticate']);
Route::post('register', [ApiController::class, 'register']);

Route::group(['middleware' => ['jwt.verify']], function() {
    
    // user endpoints
    Route::get('logout', [ApiController::class, 'logout']);
    Route::get('get_user', [ApiController::class, 'get_user']);
    
    //customer endpoints
    Route::get('customer', [CustomerController::class, 'index']);
    Route::get('customerWithPaginate', [CustomerController::class, 'pagination']);
    Route::get('customerGetDetail/{id}', [CustomerController::class, 'show']);
    Route::post('customer/insert', [CustomerController::class, 'store']);
    Route::put('customer/update/{customer}',  [CustomerController::class, 'update']);
    Route::delete('customer/delete/{customer}',  [CustomerController::class, 'destroy']);
    Route::post('customerSearchbyName', [CustomerController::class, 'searchbyname']);

    //order endpoints
    Route::get('order', [OrderController::class, 'index']);
    Route::get('orderWithPaginate', [OrderController::class, 'pagination']);
    Route::get('orderGetDetail/{id}', [OrderController::class, 'show']);
    Route::post('order/insert', [OrderController::class, 'store']);
    Route::put('order/update/{order}',  [OrderController::class, 'update']);
    Route::delete('order/delete/{order}',  [OrderController::class, 'destroy']);
    Route::post('orderSearchbyName', [OrderController::class, 'searchbyname']);

});