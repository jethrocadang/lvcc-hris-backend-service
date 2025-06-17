<?php

namespace App\Http\Controllers\Api\V1\Eth;

use Exception;
use App\Traits\ApiResponse;


use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Services\Eth\TrainingRequestService;
use App\Http\Requests\Eth\TrainingRequestRequest;
use Illuminate\Http\Request;

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

    public function getRequestByEmployeeId($employeeId, Request $request)
    {
        $filters = $request->all();
        $perPage = (int) ($filters['per_page'] ?? 10);

        $trainingRequests = $this->trainingRequestService->getTrainingRequestByEmployeeId($employeeId, $filters, $perPage);

        $meta = [
            'current_page' => $trainingRequests->currentPage(),
            'last_page' => $trainingRequests->lastPage(),
            'total' => $trainingRequests->total(),
        ];

        return $this->successResponse(
            'Training requests retrieved successfully!',
            TrainingRequestResource::collection($trainingRequests),
            200,
            $meta
        );
    }


    public function getByDepartment($departmentId, Request $request)
    {
        $requests = $this->trainingRequestService->getByDepartment($departmentId, $request);

        return $this->successResponse(
            'Training requests retrieved successfully!',
            TrainingRequestResource::collection($requests),
            200,
            [
                'current_page' => $requests->currentPage(),
                'last_page' => $requests->lastPage(),
                'total' => $requests->total(),
            ]
        );
    }



    /**
     * Display the specified resource.
     */
    public function show(int $id):JsonResponse
    {
        try {
            $trainingRequest = $this->trainingRequestService->getTrainingRequestById($id);
            return $this->successResponse('Training request retrieved successfully!', $trainingRequest, 200);
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse('Training request not found.', ['error' => $e->getMessage()], 404);
        } catch (Exception $e) {
            return $this->errorResponse('An error occurred while retrieving the training request.', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TrainingRequestRequest $request): JsonResponse
    {
        try {
            $trainingRequest = $this->trainingRequestService->createTrainingRequest($request);
            return $this->successResponse('Training request created successfully!', $trainingRequest, 201);
        } catch (Exception $e) {
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

    /**
     * Update the specified resource in storage.
     */
    public function update(TrainingRequestRequest $request, int $id): JsonResponse
    {
        try {
            $trainingRequest = $this->trainingRequestService->updateTrainingRequest($id, $request);
            return $this->successResponse('Training request updated successfully!', $trainingRequest, 200);
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse('Training request not found.', ['error' => $e->getMessage()], 404);
        } catch (Exception $e) {
            return $this->errorResponse('An error occurred while updating the training request.', ['error' => $e->getMessage()], 500);
        }
    }
}
