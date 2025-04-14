<?php

use App\Http\Controllers\Api\V1\Ats\JobApplicationFormController;
use App\Http\Controllers\Api\V1\Ats\JobPreApplicationController;
use App\Http\Controllers\Api\V1\Ats\PortalAuthController;

Route::middleware('tenant')->group(function () {

    // Public API endpoint for pre-application [Input Data: Name, Email]
    Route::post('/pre-application', [JobPreApplicationController::class, 'jobPreApplication']);
    // Public API endpoint for email verification [Input Data: verification_token]
    Route::post('/verify-email', [JobPreApplicationController::class, 'verifyEmail'])->name('email.verify');
    // Public API enpoint for application portal [Input Data: portal_token]
    Route::post('/portal-auth', [PortalAuthController::class, 'authenticate']);

    // Protected API endpoints
    Route::middleware(['tenant', 'auth.jwt.tenant', 'auth.jwt'])->group(function () {
        Route::match(['put', 'patch'], '/portal/profile', [JobApplicationFormController::class, 'updateOrCreate']);
    });
});
