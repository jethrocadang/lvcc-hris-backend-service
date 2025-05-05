<?php

use App\Http\Controllers\Api\V1\Eth\TrainingRequestController;


Route::middleware('tenant')->group(function () {

    Route::middleware(['auth.jwt'])->group(function () {

        Route::patch('eth/training-requests/{id}/officer-approve', [TrainingRequestController::class, 'officerApprove']);
        Route::patch('eth/training-requests/{id}/supervisor-approve', [TrainingRequestController::class, 'supervisorApprove']);

        Route::apiResource('eth/training-requests', TrainingRequestController::class);



    });

});