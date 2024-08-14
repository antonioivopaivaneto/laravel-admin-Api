<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ImageController;
use App\Http\Controllers\api\OrderController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::post('register',[AuthController::class,'register'])->name('register');
Route::post('login',[AuthController::class,'login'])->name('login');

Route::group(['middleware' => 'auth:api'], function() {
    Route::get('user',[UserController::class,'user'])->name('user');
    Route::put('users/info',[UserController::class,'updateInfo']);
    Route::put('users/password',[UserController::class,'updatePassword']);
    Route::post('upload',[ImageController::class,'upload']);

    Route::apiResource('/users',UserController::class );
    Route::apiResource('/roles',RoleController::class );
    Route::apiResource('/products',ProductController::class );
    Route::apiResource('/orders',OrderController::class )->only('index','show');
});

