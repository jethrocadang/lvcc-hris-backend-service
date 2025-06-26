<?php

use App\Http\Controllers\Api\V1\Eth\TrainingCoursesController;
use App\Http\Controllers\Api\V1\Eth\TrainingRequestController;
use App\Http\Controllers\Api\V1\Eth\TrainingCourseModuleController;
use App\Http\Controllers\Api\V1\Eth\EmployeeCourseProgressController;
use App\Http\Controllers\Api\V1\Eth\TrainingCourseFeedbackController;
use App\Http\Controllers\Api\V1\Eth\TrainingCourseEnrollmentController;
use App\Http\Controllers\Api\V1\Eth\ExternalTrainingAttendanceController;
use App\Http\Controllers\Api\V1\Eth\EvaluationFormController;

Route::middleware('tenant')->group(function () {

    Route::middleware(['auth.jwt'])->group(function () {

        //TRAINING REQUEST APPROVAL ROUTE FOR SUPERVISOR TO OFFICER
        Route::patch('eth/training-requests/{id}/officer-approve', [TrainingRequestController::class, 'officerApprove']);
        Route::patch('eth/training-requests/{id}/supervisor-approve', [TrainingRequestController::class, 'supervisorApprove']);

        //TRAINING REQUEST REJECT ROUTE FOR SUPERVISOR AND OFFICER
        Route::patch('eth/training-requests/{id}/officer-reject', [TrainingRequestController::class, 'officerReject']);
        Route::patch('eth/training-requests/{id}/supervisor-reject', [TrainingRequestController::class, 'supervisorReject']);

        //GET TRAINING REQEUST BY DEPARTMENT
        Route::get('eth/training-requests/by-department/{department}', [TrainingRequestController::class, 'getByDepartment']);
        Route::get('eth/training-requests/by-employee/{employeeId}', [TrainingRequestController::class, 'getRequestByEmployeeId']);

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
        Route::get('eth/external-training/by-employee/{employeeId}', [ExternalTrainingAttendanceController::class, 'getByEmployeeId']);
        Route::apiResource('eth/external-training', ExternalTrainingAttendanceController::class);

        //EVALUATION FORMS
        Route::apiResource('eth/evaluation-forms', EvaluationFormController::class);

        //GET EVALUATION FORMS BY COURSE ID
        Route::get('eth/courses/{courseId}/evaluation-forms', [EvaluationFormController::class, 'getByCourseId']);

        //EVALUATION RESPONSE OPERATIONS
        Route::post('eth/evaluation-responses', [EvaluationFormController::class, 'submitResponses']);
        Route::get('eth/evaluation-forms/{formId}/responses', [EvaluationFormController::class, 'getResponses']);
        Route::get('eth/evaluation-forms/{formId}/employees/{employeeId}/responses', [EvaluationFormController::class, 'getEmployeeResponses']);

    });

});
