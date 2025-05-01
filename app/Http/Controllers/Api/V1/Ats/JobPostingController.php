<?php

namespace App\Http\Controllers\Api\V1\Ats;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use App\Http\Requests\Ats\JobPostRequest;
use App\Services\Ats\JobPostingService;
use Illuminate\Http\JsonResponse;

class JobPostingController extends Controller
{
    use ApiResponse;

    private JobPostingService $jobPostingService;

    public function __construct(JobPostingService $jobPostingService)
    {
        $this->jobPostingService = $jobPostingService;
    }

    public function store(JobPostRequest $request): JsonResponse
    {
        $jobPost = $this->jobPostingService->createJobPost($request);

        return $jobPost
            ? $this->successResponse('Job posting created successfully!', $jobPost, 201)
            : $this->errorResponse('Failed to create job posting!', [], 500);
    }

    public function index(): JsonResponse
    {
        $jobPosts = $this->jobPostingService->getJobPosts();

        return $jobPosts->isNotEmpty()
            ? $this->successResponse('Job postings retrieved successfully!', $jobPosts)
            : $this->errorResponse('No job postings found', [], 404);
    }

    public function update(JobPostRequest $request, int $id): JsonResponse
    {
        $jobPost = $this->jobPostingService->updateJobPost($request, $id);

        return $jobPost
            ? $this->successResponse('Job posting updated successfully!', $jobPost)
            : $this->errorResponse('Failed to update job posting!', [], 500);
    }

    public function delete(int $id): JsonResponse
    {
        $deleted = $this->jobPostingService->deleteJobPost($id);

        return $deleted
            ? $this->successResponse('Job posting deleted successfully!',[])
            : $this->errorResponse('Failed to delete job posting!', [], 500);
    }
}
