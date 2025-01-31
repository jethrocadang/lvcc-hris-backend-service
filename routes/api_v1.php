<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\OauthController;

Route::controller(OauthController::class)->group(function () {
    Route::post('auth/google-callback', 'googleAuthentication');
    Route::get('auth/test', [OauthController::class, 'test']);
});

Route::get('test', function () {
    return "test - API v1";
});
