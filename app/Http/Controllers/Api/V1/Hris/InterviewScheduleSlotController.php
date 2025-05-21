<?php

namespace App\Http\Controllers\Api\V1\Hris;

use App\Http\Controllers\Controller;
use App\Http\Requests\InterviewScheduleSlotRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Traits\ApiResponse;
use Exception;
use App\Services\Hris\InterviewScheduleSlotService;

class InterviewScheduleSlotController extends Controller
{
    use ApiResponse;
    private InterviewScheduleSlotService $interviewScheduleSlotService;
    public function __construct(InterviewScheduleSlotService $interviewScheduleSlotService)
    {
        $this->interviewScheduleSlotService = $interviewScheduleSlotService;
    }

    public function index(): JsonResponse
    {
        $interviewScheduleSlots = $this->interviewScheduleSlotService->getInterviewScheduleSlots();

        return $interviewScheduleSlots->isNotEmpty()
            ? $this->successResponse('Interview schedule slots retrieved successfully!', $interviewScheduleSlots, 200)
            : $this->errorResponse('No interview schedule slots found', [], 404);
    }

    public function show(int $id): JsonResponse
    {
        try {
            $interviewScheduleSlot = $this->interviewScheduleSlotService->getInterviewScheduleSlotById($id);
            return $this->successResponse('Interview schedule slot retrieved successfully!', $interviewScheduleSlot);
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse($e->getMessage(), [], 404);
        } catch (Exception $e) {
            return $this->errorResponse('Failed to retrieve interview schedule slot!', ['error' => $e->getMessage()], 500);
        }
    }

    public function store(InterviewScheduleSlotRequest $request): JsonResponse
    {
        try {
            $interviewScheduleSlot = $this->interviewScheduleSlotService->createInterviewScheduleSlot($request);
            return $this->successResponse('Interview schedule slot created successfully!', $interviewScheduleSlot, 201);
        } catch (Exception $e) {
            return $this->errorResponse('An error occurred while creating the interview schedule slot.', ['error' => $e->getMessage()], 500);
        }
    }
    public function update(InterviewScheduleSlotRequest $request, int $id): JsonResponse
    {
        try {
            $interviewScheduleSlot = $this->interviewScheduleSlotService->updateInterviewScheduleSlot($request, $id);
            return $this->successResponse('Interview schedule slot updated successfully!', $interviewScheduleSlot);
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse($e->getMessage(), [], 404);
        } catch (Exception $e) {
            return $this->errorResponse('Failed to update interview schedule slot!', ['error' => $e->getMessage()], 500);
        }
    }
    public function destroy(int $id): JsonResponse
    {
        try {

            $this->interviewScheduleSlotService->deleteInterviewScheduleSlot($id);

            return $this->successResponse('Interview schedule slot deleted successfully!', [], 200);
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse($e->getMessage(), [], 404);
        } catch (Exception $e) {
            return $this->errorResponse('Failed to delete interview schedule slot!', ['error' => $e->getMessage()], 500);
        }
    }
}
