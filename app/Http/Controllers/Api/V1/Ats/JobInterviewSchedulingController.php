<?php

namespace App\Http\Controllers\Api\V1\Ats;

use App\Http\Controllers\Controller;
use App\Http\Requests\JobInterviewSchedulingRequest;
use App\Services\Ats\JobInterviewSchedulingService;
use App\Traits\ApiResponse;
use Exception;

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
            $interviewSchedule = $this->jobInterviewScheduling->createSchedule($request);

            return $this->successResponse('Created', [$interviewSchedule], 200);
        } catch (Exception $e) {
            return $this->errorResponse('Failed', [$e->getMessage()], 500);
        }
    }
}
