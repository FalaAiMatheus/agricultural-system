<?php

use App\Http\Controllers\Auth\AuthUserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ProductionUnitController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\RuralProducerController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('/login', LoginController::class);
    Route::post('/logout', LogoutController::class)->middleware('auth:sanctum');
    Route::get('/user', AuthUserController::class)->middleware('auth:sanctum');
    Route::post('/register', RegisterController::class);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('producers', RuralProducerController::class);
    Route::apiResource('properties', PropertyController::class);
    Route::apiResource('production-units', ProductionUnitController::class);
});
