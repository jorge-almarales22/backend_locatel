<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [App\Http\Controllers\AuthController::class, 'login']);
Route::post('/create-account', [App\Http\Controllers\AccountController::class, 'createAccount']);

Route::group(['prefix' => 'auth'], function () {
    Route::group(['middleware' => ['jwt.auth']], function () {
        Route::get('/check-balance/{accountNumber}', [App\Http\Controllers\AccountController::class, 'balanceAccount']);
        Route::post('/refresh', [App\Http\Controllers\AuthController::class, 'refresh']);
        Route::post('/withdrawals', [App\Http\Controllers\AccountController::class, 'withdrawals']);
        Route::post('/consign-money', [App\Http\Controllers\AccountController::class, 'consignMoney']);
    });
});


