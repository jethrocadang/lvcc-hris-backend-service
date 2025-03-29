<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\PasswordController;
use App\Http\Controllers\Api\V1\Ats\JobPostingController;
use App\Http\Controllers\Api\V1\Ats\JobApplicationController;
use App\Http\Controllers\Api\V1\ActivityLogController;
use App\Http\Controllers\Api\V1\DepartmentController;
use App\Http\Controllers\Api\V1\PositionController;
use App\Http\Controllers\Api\V1\DeptPosController;
use App\Http\Controllers\Api\V1\PolicyController;
use App\Http\Controllers\Api\V1\UserAgreementController;
use App\Http\Controllers\Api\V1\UserPolicyAgreementController;
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

Route::controller(AuthController::class)->group(function () {
    Route::post('auth/google-callback', 'googleAuthentication');
    Route::get('auth/test', 'test');
    Route::post('auth/login', 'login');
    Route::patch('auth/update-password', 'updatePassword');
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

Route::controller(PositionController::class)->group(function () {
    Route::post('/create/position', 'createPosition');
    Route::get('/get/positions', 'getPositions');
    Route::put('/update/position/{id}', 'updatePosition');
    Route::delete('/delete/position/{id}', 'deletePosition');
});

Route::controller(DeptPosController::class)->group(function () {
    Route::post('/create/department-position', 'createDeptPos');
    Route::get('/get/department-positions', 'getDeptPos');
    Route::put('/update/department-position/{id}', 'updateDeptPos');
    Route::delete('/delete/department-position/{id}', 'deleteDeptPos');
});

/**
 * ==============================
 *  Policy, User Agreement, and User Policy Agreements Routes
 * ==============================
 */

Route::controller(PolicyController::class)->group(function () {
    Route::post('/create/policy', 'createPolicy');
    Route::get('/get/policies', 'getPolicies');
    Route::put('/update/policy/{id}', 'updatePolicy');
    Route::delete('/delete/policy/{id}', 'deletePolicy');
});

Route::controller(UserAgreementController::class)->group(function () {
    Route::post('/create/user-agreement', 'createUserAgreement');
    Route::get('/get/user-agreements', 'getUserAgreements');
});

Route::controller(UserPolicyAgreementController::class)->group(function () {
    Route::post('/create/user-policy-agreement', 'createUserPolicyAgreement');
    Route::get('/get/user-policy-agreements', 'getUserPolicyAgreements');
    Route::put('/update/user-policy-agreement/{id}', 'updateUserPolicyAgreement');
    Route::delete('/delete/user-policy-agreement/{id}', 'deleteUserPolicyAgreement');
});

/**
 * ==============================
 *  Applicant Registration Routes
 * ==============================
 */


 /**
 * ==============================
 *  Mail Routes
 * ==============================
 */

 Route::middleware('tenant')->group(function() {
     Route::post('/pre-register', [JobApplicationController::class, 'createApplication']);
     Route::get('/test-api', [JobApplicationController::class, 'test']);
     Route::get('/verify-email/{token}', [JobApplicationController::class, 'verifyEmail']);
    // routes
});

