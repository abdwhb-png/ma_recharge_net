<?php

use App\Http\Controllers\FormDataController;
use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TokenController;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::controller(HomeController::class)->group(function () {
        Route::get('/dashboard', 'dashboard')->name('dashboard');
        Route::get('/token-manager', 'tokenManager')->name('token-manager');
        Route::post('setting/update', 'settingUpdate')->name('setting.update');
    });

    Route::resource('form-data', FormDataController::class)->only(['update', 'destroy']);
    Route::resource('token', TokenController::class)->only(['store', 'update', 'destroy']);
});

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
