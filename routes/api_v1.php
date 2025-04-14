<?php
// // App\Http\Controllers\Api\V1;
use App\Http\Controllers\Api\V1\AuthController;

// //App\Http\Controllers\Api\V1\Hris;
// use App\Http\Controllers\Api\V1\Hris\DepartmentController;
// use App\Http\Controllers\Api\V1\Hris\JobPositionController;
// use App\Http\Controllers\Api\V1\Hris\PolicyController;
// // use App\Http\Controllers\Api\V1\Hris\DepartmentJobPositionController;


// // App\Http\Controllers\Api\V1\Ats;
// use App\Http\Controllers\Api\V1\Ats\JobPostingController;
// use App\Http\Controllers\Api\V1\Ats\JobApplicationController;


// use App\Http\Controllers\Api\V1\ActivityLogController;
// use App\Http\Controllers\Api\V1\EmailTemplateController;
// use App\Http\Controllers\Api\V1\Hris\UserPolicyAgreementController;
// use App\Http\Middleware\JwtMiddleware;
// use Illuminate\Support\Facades\Route;

// /**
//  * ==============================
//  *  Test Routes - For API checking
//  * ==============================
//  */

// Route::get('test', function () {
//     return 'test - API v1';
// });

// /**
//  * ==============================
//  *  Authentication Routes (JWT + OAuth)
//  * ==============================
//  */

Route::controller(AuthController::class)->group(function () {
    Route::post('auth/google-callback', 'googleAuthentication');
    Route::get('auth/test', 'test');
    Route::post('auth/login', 'login');
    Route::patch('auth/update-password', 'updatePassword');
});

// /**
//  * ==============================
//  *  For protected routes, you need to group them inside JwtMiddleware and RoleMiddleware
//  * ==============================
//  */
// Route::middleware(JwtMiddleware::class)->group(function () {
//     Route::controller(DepartmentController::class)->group(function () {
//         Route::post('/create/department', 'createDepartment');
//         Route::get('/get/departments', 'getDepartments');
//         Route::put('/update/department/{id}', 'updateDepartment');
//         Route::delete('/delete/department/{id}', 'deleteDepartment');
//     });
// });

// /**
//  * ==============================
//  *  Activity Log Routes
//  * ==============================
//  */
// Route::get('/get/activity-logs', [ActivityLogController::class, 'getActivityLogs']);

// /**
//  * ==============================
//  *  Job Post CRUD Routes
//  * ==============================
//  */
// Route::controller(JobPostingController::class)->group(function () {
//     Route::post('/create/job-post', 'createJobPost');
//     Route::get('/get/job-post', 'getJobPost');
//     Route::put('/update/job-post/{id}', 'updateJobPost');
//     Route::delete('/delete/job-post/{id}', 'deleteJobPost');
// });

// /**
//  * ==============================
//  *  Department, Position, and Department_position Routes
//  * ==============================
//  */
// Route::apiResource('departments', DepartmentController::class);
// Route::apiResource('job-positions', JobPositionController::class);

// // Route::controller(DepartmentJobPositionController::class)->group(function () {
// //     Route::post('/create/department-job-position', 'attachDepartmentJobPosition');
// //     Route::get('/get/department-job-positions', 'getDepartmentJobPositions');
// //     // Route::put('/update/department-job-position/{id}', 'updateDeptPos');
// //     Route::delete('/delete/department-job-position/{id}', 'deleteDepartmentPosition');
// // });

// /**
//  * ==============================
//  *  Policy and User Policy Agreements Routes
//  * ==============================
//  */

//  Route::apiResource('policies', PolicyController::class);



// Route::controller('user-policy', UserPolicyAgreementController::class);

// /**
//  * ==============================
//  *  Applicant Registration Routes
//  * ==============================
//  */

// Route::middleware('tenant')->group(function () {
//     Route::post('/pre-register', [JobApplicationController::class, 'createApplication']);
//     Route::get('/test-api', [JobApplicationController::class, 'test']);
//     Route::get('/verify-email/{token}', [JobApplicationController::class, 'verifyEmail'])->name('email.verify');
//     // routes
// });

// /**
//  * ==============================
//  *  Email Template Routes
//  * ==============================
//  */
// Route::apiResource('email-templates', EmailTemplateController::class);
