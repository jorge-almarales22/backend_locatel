<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/check-balance/{accountNumber}', [App\Http\Controllers\AccountController::class, 'balanceAccount']);
Route::post('/withdrawals', [App\Http\Controllers\AccountController::class, 'withdrawals']);
Route::post('/consign-money', [App\Http\Controllers\AccountController::class, 'consignMoney']);
Route::post('/create-account', [App\Http\Controllers\AccountController::class, 'createAccount']);