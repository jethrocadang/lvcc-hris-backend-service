<?php

use App\Http\Controllers\Api\V1\Ats\JobApplicationController;
use App\Http\Controllers\Api\V1\Ats\PortalAuthController;

Route::middleware('tenant')->group(function () {

    Route::post('/verify-email', [JobApplicationController::class, 'verifyEmail'])->name('email.verify');
    Route::get('/test-api', [JobApplicationController::class, 'test']);
    Route::post('/pre-register', [JobApplicationController::class, 'createApplication']);
    Route::apiResource('job-applicants', JobApplicationController::class);

    Route::post('/portal-auth', [PortalAuthController::class, 'authenticate']);


    // routes
});
