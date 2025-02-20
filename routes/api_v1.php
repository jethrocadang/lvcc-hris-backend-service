<?php

use App\Http\Controllers\Api\V1\OauthController;
use App\Http\Controllers\Api\V1\PasswordController;
use App\Http\Controllers\Api\V1\JobPostingController;
use App\Http\Controllers\Api\V1\ActivityLogController;
use App\Http\Controllers\Api\V1\DepartmentController;
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
    Route::get('auth/test', 'test');
    Route::post('auth/login', 'login');
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
 *  Activity Log Routes
 * ==============================
 */
Route::get('/get/activity-logs', [ActivityLogController::class, 'getActivityLogs']);

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

/**
 * ==============================
 *  Setting Password Routes
 * ==============================
 */
Route::controller(PasswordController::class)->group(function () {
    Route::post('/create/password' , 'createPassword');
    Route::post('/update/password' , 'updatePassword');
});

/**
 * ==============================
 *  Department, Position, and Department_position Routes
 * ==============================
 */
Route::controller(DepartmentController::class)->group(function () {
    Route::post('/create/department', 'createDepartment');
    Route::get('/get/departments', 'getDepartments');
    Route::put('/update/department/{id}', 'updateDepartment');
    Route::delete('/delete/department/{id}', 'deleteDepartment');
});