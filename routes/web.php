<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();
Route::get('/login-totp', [App\Http\Controllers\Auth\LoginController::class, 'loginTotp'])->name('login-totp');
Route::post('/login-totp', [App\Http\Controllers\Auth\LoginController::class, 'loginTotpVerify'])->name('login-totp');


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::post('/totp-enable', [App\Http\Controllers\HomeController::class, 'totpEnable'])->name('totp-enable');
