<?php

namespace App\Http\Controllers\Api\V1\Ats;

use App\Http\Controllers\Controller;
use App\Http\Requests\JobInterviewSchedulingRequest;
use App\Http\Resources\JobInterviewSchedulingResource;
use App\Services\Ats\JobInterviewSchedulingService;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class JobInterviewSchedulingController extends Controller
{
    use ApiResponse;

    private JobInterviewSchedulingService $jobInterviewScheduling;

    public function __construct(JobInterviewSchedulingService $jobInterviewScheduling)
    {
        $this->jobInterviewScheduling = $jobInterviewScheduling;
    }

    public function store(JobInterviewSchedulingRequest $request)
    {
        try {
            $interviewSchedule = $this->jobInterviewScheduling->createScheduleByApplicant($request);
            return $this->successResponse('Created', [$interviewSchedule], 200);
        } catch (Exception $e) {
            return $this->errorResponse('Failed', [$e->getMessage()], 500);
        }
    }

    public function scheduleForNextPhase(JobInterviewSchedulingRequest $request)
    {

        try {
            $interviewSchedule = $this->jobInterviewScheduling->createScheduleByAdmin($request);
            return $this->successResponse('Created', [$interviewSchedule], 200);
        } catch (Exception $e) {
            return $this->errorResponse('Failed', [$e->getMessage()], 500);
        }
    }

    public function index(Request $request): JsonResponse
    {
        $filters = $request->all();
        $perPage = (int) ($filters['per_page'] ?? 10);

        $schedules = $this->jobInterviewScheduling->getInterviewSchedules($filters, $perPage);

        $meta = [
            'current_page' => $schedules->currentPage(),
            'last_page' => $schedules->lastPage(),
            'total' => $schedules->total(),
        ];

        return $this->successResponse(
            'Interview schedules retrieved successfully!',
            JobInterviewSchedulingResource::collection($schedules),
            200,
            $meta
        );
    }

    public function getByJobApplication(int $jobApplicationId)
    {
        try {
            $interviewSchedules = $this->jobInterviewScheduling->getInterviewSchedulesByJobApplication($jobApplicationId);
            return $this->successResponse('Fetched', $interviewSchedules, 200);
        } catch (Exception $e) {
            return $this->errorResponse('Failed', [$e->getMessage()], 500);
        }
    }
}
