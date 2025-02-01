<?php

use App\Http\Controllers\Api\V1\OauthController;
use Illuminate\Support\Facades\Route;

/**
 * ==============================
 *  Test Routes - For API checking
 * ==============================
 */

Route::get('test', function () {
    return 'test - API v1';
});

/**
 * ==============================
 *  Authentication Routes (JWT + OAuth)
 * ==============================
 */

Route::controller(OauthController::class)->group(function () {
    Route::post('auth/google-callback', 'googleAuthentication');
    Route::get('auth/test', [OauthController::class, 'test']);
});
