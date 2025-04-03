<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\FormDataController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::controller(ApiController::class)->group(function () {
    Route::post('form-data', [FormDataController::class, 'store']);
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
