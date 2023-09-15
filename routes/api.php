<?php

use App\Http\Controllers\ProxyController;
use App\Http\Middleware\ProxyUrlReplace;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('v1')->group(function () {
    Route::prefix('proxy')->name('proxy.')->group(function () {
        Route::prefix('rent_house')->middleware(ProxyUrlReplace::class)->name('rent_house.')->group(function () {
            Route::get('/get_csrf_token_and_cookies', [ProxyController::class, 'getCsrfTokenAndCookies'])->name('get_csrf_token_and_cookies');
            Route::get('/list', [ProxyController::class, 'proxy'])->name('list');
            Route::get('/detail_data', [ProxyController::class, 'proxy'])->name('detail_data');
        });
    });
});
