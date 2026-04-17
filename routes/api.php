<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\SmsMessageController;
use Illuminate\Support\Facades\Route;

Route::middleware(['force.json', 'throttle:20,1'])->post('login', [AuthController::class, 'login']);

Route::middleware(['force.json', 'auth:sanctum'])->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);

    Route::get('sms-messages', [SmsMessageController::class, 'index']);
    Route::get('sms-messages/{smsMessage}', [SmsMessageController::class, 'show']);
    Route::put('sms-messages/{sms}', [SmsMessageController::class, 'update']);
});
