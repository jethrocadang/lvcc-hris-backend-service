<?php

use App\Http\Controllers\Api\V1\Ats\AtsEmailtemplateController;
use App\Http\Controllers\Api\V1\Ats\JobApplicationAdminController;
use App\Http\Controllers\Api\V1\Ats\JobApplicationFormController;
use App\Http\Controllers\Api\V1\Ats\JobApplicationPhasesController;
use App\Http\Controllers\Api\V1\Ats\JobApplicationProgressController;
use App\Http\Controllers\Api\V1\Ats\JobInterviewSchedulingController;
use App\Http\Controllers\Api\V1\Ats\JobPostingController;
use App\Http\Controllers\Api\V1\Ats\JobPreApplicationController;
use App\Http\Controllers\Api\V1\Ats\PortalAuthController;
use App\Http\Controllers\Api\V1\Ats\JobSelectionOptionsController;
use App\Http\Controllers\Api\V1\Ats\AdminDashBoardContoller;

Route::middleware('tenant')->group(function () {

    //Public Route for Job Postings
    Route::get('ats/job-posts', [JobPostingController::class, 'index']);
    Route::get('ats/ats-email-templates', [AtsEmailtemplateController::class, 'index']);
    Route::get('ats/ats-email-templates/{id}', [AtsEmailtemplateController::class, 'show']);
    Route::get('ats/job-posts/{id}', [JobPostingController::class, 'show']);

    // Public API endpoint for pre-application [Input Data: Name, Email]
    Route::post('ats/pre-application', [JobPreApplicationController::class, 'jobPreApplication']);
    // Public API endpoint for email verification [Input Data: verification_token]
    Route::post('ats/verify-email', [JobPreApplicationController::class, 'verifyEmail'])->name('email.verify');
    // Public API enpoint for application portal [Input Data: portal_token]
    Route::post('ats/portal-auth', [PortalAuthController::class, 'authenticate']);

    Route::post('ats/portal-auth/refresh-token', [PortalAuthController::class, 'refreshToken']);

    Route::apiResource('ats/job-application-phases', JobApplicationPhasesController::class)->except(['update']);

    // ** PORTAL ENDPOINTS
    Route::middleware(['auth.jwt.tenant', 'auth.jwt'])->group(function () {
        Route::match(['put', 'patch'], 'ats/portal/profile', [JobApplicationFormController::class, 'updateOrCreate']);
        Route::post('ats/job-application-form/final-submit', [JobApplicationFormController::class, 'finalSubmit']);
        Route::get('ats/job-application-progress/{id}', [JobApplicationProgressController::class, 'getAllProgressByUser']);
        Route::post('ats/select-interview-schedule', [JobInterviewSchedulingController::class, 'store']);
        Route::get('ats/interview-schedule/{id}', [JobInterviewSchedulingController::class, 'getByJobApplication']);
        Route::get('ats/job-applicant/{id}', [JobApplicationFormController::class, 'show']);
        Route::get('ats/job-selection-options/by-job-application/{jobApplicationId}', [JobSelectionOptionsController::class, 'showByJobApplicationId']);
    });

    // ** ADMIN & REVIEWER ENDPOINTS
    Route::middleware(['auth.jwt'])->group(function () {
        Route::apiResource('ats/job-posts', JobPostingController::class)->except(['index', 'show']);
        Route::post('ats/admin/update-application-progress', [JobApplicationProgressController::class, 'updatePhase']);
        Route::apiResource('ats/job-application-phases', JobApplicationPhasesController::class)->except(['index']);
        Route::controller(JobApplicationAdminController::class)->group(function () {
            Route::get('ats/admin/view-all-applications', 'getAllJobApplications');
            Route::get('ats/admin/view-all-applications/{slug}', 'getAllJobApplicationsBySlug');
            Route::get('ats/admin/view-application/{id}', 'getJobApplication');
        });
        Route::post('ats/admin/set-interview-schedule', [JobInterviewSchedulingController::class, 'scheduleForNextPhase']);
        Route::apiResource('ats/ats-email-templates', AtsEmailtemplateController::class)->except(['index', 'show']);
        Route::get('ats/select-interview-schedule', [JobInterviewSchedulingController::class, 'index']);

        // Dashboard metrics endpoints
        Route::controller(AdminDashBoardContoller::class)->prefix('ats/admin/dashboard')->group(function () {
            Route::get('/metrics', 'getDashboardMetrics');
            Route::get('/monthly-applications', 'getMonthlyApplicationCounts');
            Route::get('/funnel', 'applicationFunnel');
        });
    });
});
