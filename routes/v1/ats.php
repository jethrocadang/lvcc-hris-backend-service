<?php

use App\Http\Controllers\Api\V1\Ats\JobApplicationFormController;
use App\Http\Controllers\Api\V1\Ats\JobApplicationProgressController;
use App\Http\Controllers\Api\V1\Ats\JobInterviewSchedulingController;
use App\Http\Controllers\Api\V1\Ats\JobPostingController;
use App\Http\Controllers\Api\V1\Ats\JobPreApplicationController;
use App\Http\Controllers\Api\V1\Ats\PortalAuthController;

Route::middleware('tenant')->group(function () {

    //Public Route for Job Postings
    Route::get('ats/job-posts', [JobPostingController::class, 'index']);
    Route::apiResource('ats/job-posts', JobPostingController::class)->except(['index']);

    // Public API endpoint for pre-application [Input Data: Name, Email]
    Route::post('ats/pre-application', [JobPreApplicationController::class, 'jobPreApplication']);
    // Public API endpoint for email verification [Input Data: verification_token]
    Route::post('ats/verify-email', [JobPreApplicationController::class, 'verifyEmail'])->name('email.verify');
    // Public API enpoint for application portal [Input Data: portal_token]
    Route::post('ats/portal-auth', [PortalAuthController::class, 'authenticate']);


    // ** PORTAL ENDPOINTS
    Route::middleware(['auth.jwt.tenant', 'auth.jwt'])->group(function () {
        Route::match(['put', 'patch'], 'ats/portal/profile', [JobApplicationFormController::class, 'updateOrCreate']);
        Route::get('ats/job-application-progress', [JobApplicationProgressController::class, 'getAllProgressByUser']);
        Route::post('ats/select-interview-schedule',[JobInterviewSchedulingController::class, 'store']);
    });

    // ** ADMIN & REVIEWER ENDPOINTS
    Route::middleware(['auth.jwt'])->group(function () {
        Route::post('admin/update-phase-two',[JobApplicationProgressController::class, 'updatePhaseTwo']);
    });
});
