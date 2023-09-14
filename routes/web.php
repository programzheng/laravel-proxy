<?php

use App\Http\Controllers\ProxyController;
use App\Http\Middleware\EncryptCookies;
use App\Http\Middleware\ProxyUrlReplace;
use App\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::prefix('proxy')->name('proxy.')->group(function () {
    Route::prefix('rent_house')->middleware(ProxyUrlReplace::class)->name('rent_house.')->group(function () {
        Route::get('/', [ProxyController::class, 'index'])->name('index');
    });
});
