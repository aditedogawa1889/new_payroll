<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\HrisIntegrationController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware(['auth:sanctum', 'role:hris-integration', 'throttle:60,1'])->group(function () {
    Route::post('/hris/integrations', [HrisIntegrationController::class, 'store']);
});
