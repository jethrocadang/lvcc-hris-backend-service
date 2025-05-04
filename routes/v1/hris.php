<?php

/**
 * Version 1 API Endpoints for HRIS
 *
 * This file contains the endpoints related to the HRIS (Human Resource Information System) for version 1 of the API.
 * These endpoints primarily interact with the landlord database and handle main authentication processes for employee management and employee training.
 *
 * * Middleware:*
 * - `auth.jwt`: Ensures the user is authenticated via JWT token.
 * - `spatie`: Handles roles and permissions checks for accessing specific resources.
 * - `cors`: Manages Cross-Origin Resource Sharing (CORS) for handling requests from different domains.
 *
 * Endpoints in this file include authentication, Google OAuth callbacks, password updates, and basic test routes.
 */

use Illuminate\Support\Facades\Route;

// App\Http\Controllers\Api\V1\Hris
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\Hris\EmailTemplateController;
use App\Http\Controllers\Api\V1\Hris\PolicyController;
use App\Http\Controllers\Api\V1\Hris\EmployeeController;
use App\Http\Controllers\Api\V1\Hris\DepartmentController;
use App\Http\Controllers\Api\V1\Hris\JobPositionController;
use App\Http\Controllers\Api\V1\Hris\UserPolicyAgreementController;
use App\Http\Controllers\Api\V1\Hris\InterviewScheduleSlotController;



// Authentication routes: Public routes
Route::controller(AuthController::class)->group(function () {

    // Test API route to verify authentication/middleware (For debugging purposes)
    Route::get('auth/test', 'test');

    // Google OAuth login callback (used for Google One Tap or OAuth sign-ins)
    Route::post('auth/google-callback', 'googleAuthentication');

    // User login with email and password credentials
    Route::post('auth/login', 'login');

    // Update user password (should be moved under auth.jwt middleware for protection)
    Route::patch('auth/update-password', 'updatePassword');
});


// Protected routes for Main Users (requires auth.jwt)
Route::middleware(['auth.jwt'])->group(function () {

    // Custom routes for getting, assigning/removing job positions to departments
    Route::get('hris/departments/with-job-positions', [DepartmentController::class, 'getAllWithJobPositions']);
    Route::post('hris/departments/attach-job-position', [DepartmentController::class, 'attachDepartmentJobPosition']);
    Route::delete('hris/departments/{departmentId}/job-position/{jobPositionId}', [DepartmentController::class, 'detachDepartmentJobPosition']);

    // Department management (CRUD)
    Route::apiResource('hris/departments', DepartmentController::class); //done

    // Job Position management (CRUD)
    Route::apiResource('hris/job-positions', JobPositionController::class); //done

    // Data Privacy Policies management (CRUD)
    Route::apiResource('hris/policies', PolicyController::class); //done

    // User Policy Agreements management (CRUD)
    Route::apiResource('hris/user-policy', UserPolicyAgreementController::class); 

    // Email Templates management (CRUD)
    Route::apiResource('hris/email-templates', EmailTemplateController::class); //done

    // Interview Schedule Slots management (CRUD)
    Route::apiResource('hris/interview-slots', InterviewScheduleSlotController::class); //done

    // Update only the employee's additional information or the core
    Route::patch('hris/employees/{id}/information', [EmployeeController::class, 'updateInformationOnly']);
    Route::patch('hris/employees/{id}/core', [EmployeeController::class, 'updateEmployeeOnly']);

    // Employees management (CRUD)
    Route::apiResource('hris/employees', EmployeeController::class); //done

});
