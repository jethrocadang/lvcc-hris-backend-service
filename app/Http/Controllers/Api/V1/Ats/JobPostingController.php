<?php

namespace App\Http\Controllers\Api\V1\Ats;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ats\JobPostGetRequest;
use App\Traits\ApiResponse;
use App\Http\Requests\Ats\JobPostRequest;
use App\Http\Resources\JobPostResource;
use App\Services\Ats\JobPostingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

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

    public function index(JobPostGetRequest $request): JsonResponse
    {
        $filters = $request->validated();
        $perPage = (int) ($filters['per_page'] ?? 10);

        $jobPosts = $this->jobPostingService->getJobPosts($filters, $perPage);

        // Pagination info
        $meta = [
            'current_page' => $jobPosts->currentPage(),
            'last_page' => $jobPosts->lastPage(),
            'total' => $jobPosts->total(),
        ];

        // Return data with meta
        return $this->successResponse(
            'Job postings retrieved successfully!',
            JobPostResource::collection($jobPosts),
            200,
            $meta
        );
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
            ? $this->successResponse('Job posting deleted successfully!', [])
            : $this->errorResponse('Failed to delete job posting!', [], 500);
    }
}
