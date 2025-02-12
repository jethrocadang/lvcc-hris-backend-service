<?php

use App\Http\Controllers\Api\V1\OauthController;
use App\Http\Controllers\Api\V1\JobPostingController;
use App\Http\Middleware\JwtMiddleware;
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

/**
 * ==============================
 *  For protected routes, you need to group them inside JwtMiddleware and RoleMiddleware
 * ==============================
 */

 Route::middleware(JwtMiddleware::class)->get('jwt', function () {
    return response()->json(['message' => 'Authenticated user']);
});

/**
 * ==============================
 *  Job Post CRUD Routes
 * ==============================
 */
Route::controller(JobPostingController::class)->group(function () {
    Route::post('/create/job-post', 'createJobPost');
    Route::get('/get/job-post', 'getJobPost');
    Route::put('/update/job-post/{id}', 'updateJobPost');
    Route::delete('/delete/job-post/{id}', 'deleteJobPost');
});
