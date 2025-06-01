<?php

namespace App\Http\Controllers\Api\V1\Eth;

use Exception;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Services\Eth\TrainingRequestService;
use App\Http\Requests\Eth\TrainingRequestRequest;

use App\Http\Resources\Eth\TrainingRequestResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class TrainingRequestController extends Controller
{
    use ApiResponse;

    private TrainingRequestService $trainingRequestService;

    public function __construct(TrainingRequestService $trainingRequestService)
    {
        $this->trainingRequestService = $trainingRequestService;
    }


    public function index(Request $request)
    {
        $filters = $request->all();
        $perPage = (int) ($filters['per_page'] ?? 10);

        $trainingRequest = $this->trainingRequestService->getTrainingRequest($filters, $perPage);

        $meta = [
            'current_page' => $trainingRequest->currentPage(),
            'last_page' => $trainingRequest->lastPage(),
            'total' => $trainingRequest->total(),
        ];

        return $this->successResponse(
            'Training requests retrieved successfully!',
            TrainingRequestResource::collection($trainingRequest),
            200,
            $meta
        );
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

    /**
     * Rejection of training requests by supervisor and officer
     */
        public function officerReject($id)
    {
        return $this->trainingRequestService->rejectByOfficer($id);
    }

    public function supervisorReject($id)
    {
        return $this->trainingRequestService->rejectBySupervisor($id);
    }

}
