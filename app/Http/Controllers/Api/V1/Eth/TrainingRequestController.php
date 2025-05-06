<?php

namespace App\Http\Controllers\Api\V1\Eth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Eth\TrainingRequestRequest;
use App\Services\Eth\TrainingRequestService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use Exception;


class TrainingRequestController extends Controller
{
    use ApiResponse;

    private TrainingRequestService $trainingRequestService;

    public function __construct(TrainingRequestService $trainingRequestService)
    {
        $this->trainingRequestService = $trainingRequestService;
    }


    public function index()
    {
        $trainingRequest = $this->trainingRequestService->getTrainingRequest();

        return $trainingRequest->isNotEmpty()
            ? $this->successResponse('Traning requests retrieved successfully!', $trainingRequest)
            : $this->errorResponse('No training requests found', [], 404);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TrainingRequestRequest $request): JsonResponse
    {
        try{
            $trainingRequest = $this->trainingRequestService->createTrainingRequest($request);
            return $this->successResponse('Training request created successfully!', $trainingRequest, 201);
        }catch (Exception $e){
            return $this->errorResponse('An error occured while creating the training request.', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Approval of training requests by supervisor and officer
     */

    public function officerApprove($id)
    {
        return $this->trainingRequestService->approveByOfficer($id);
    }

    public function supervisorApprove($id)
    {
        return $this->trainingRequestService->approveBySupervisor($id);
    }

}
