<?php

use App\Http\Controllers\Api\V1\Eth\TrainingCoursesController;
use App\Http\Controllers\Api\V1\Eth\TrainingRequestController;
use App\Http\Controllers\Api\V1\Eth\TrainingCourseEnrollmentController;
use App\Http\Controllers\Api\V1\Eth\TrainingCourseModuleController;

Route::middleware('tenant')->group(function () {

    Route::middleware(['auth.jwt'])->group(function () {

        //TRAINING REQUEST APPROVAL ROUTE FOR SUPERVISOR TO OFFICER
        Route::patch('eth/training-requests/{id}/officer-approve', [TrainingRequestController::class, 'officerApprove']);
        Route::patch('eth/training-requests/{id}/supervisor-approve', [TrainingRequestController::class, 'supervisorApprove']);
        //TRAINING REQUEST CRUD
        Route::apiResource('eth/training-requests', TrainingRequestController::class);


        //TRAINING COURSE CRUD
        Route::apiResource('eth/training-courses', TrainingCoursesController::class);

        //CANCELLING ENROLLMENT
        Route::patch('eth/course-enrollment/{id}/cancel', [TrainingCourseEnrollmentController::class, 'cancel']);
        //TRAINING COURSE ENROLLMENT
        Route::apiResource('eth/course-enrollment', TrainingCourseEnrollmentController::class);

        //TRAINING COURSE MODULE
        Route::apiResource('eth/course-modules', TrainingCourseModuleController::class);

    });

});