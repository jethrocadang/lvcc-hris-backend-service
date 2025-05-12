<?php

use App\Http\Controllers\Api\V1\Eth\TrainingCoursesController;
use App\Http\Controllers\Api\V1\Eth\TrainingRequestController;
use App\Http\Controllers\Api\V1\Eth\TrainingCourseModuleController;
use App\Http\Controllers\Api\V1\Eth\EmployeeCourseProgressController;
use App\Http\Controllers\Api\V1\Eth\TrainingCourseFeedbackController;
use App\Http\Controllers\Api\V1\Eth\TrainingCourseEnrollmentController;
use App\Http\Controllers\Api\V1\Eth\ExternalTrainingAttendanceController;

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

        //EMPLOYEE COURSE PROGRESS
        Route::apiResource('eth/course-progress', EmployeeCourseProgressController::class);

        //TRAINING COURSE FEEDBACK
        Route::apiResource('eth/course-feedback', TrainingCourseFeedbackController::class);

        //EXTERNAL TRAINING ATTENDANCE
        Route::apiResource('eth/external-training', ExternalTrainingAttendanceController::class);

    });

});