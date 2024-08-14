<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\ImageController;
use App\Http\Controllers\api\OrderController;
use App\Http\Controllers\api\PermissionController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::post('register',[AuthController::class,'register'])->name('register');
Route::post('login',[AuthController::class,'login'])->name('login');

Route::group(['middleware' => 'auth:api'], function() {
    Route::get('chart',[DashboardController::class,'chart'])->name('chart');
    Route::get('user',[UserController::class,'user'])->name('user');
    Route::put('users/info',[UserController::class,'updateInfo']);
    Route::put('users/password',[UserController::class,'updatePassword']);
    Route::post('upload',[ImageController::class,'upload']);
    Route::get('export',[OrderController::class,'export']);

    Route::apiResource('/users',UserController::class );
    Route::apiResource('/roles',RoleController::class );
    Route::apiResource('/products',ProductController::class );
    Route::apiResource('/orders',OrderController::class )->only('index','show');
    Route::apiResource('/permissions',PermissionController::class )->only('index');
});

