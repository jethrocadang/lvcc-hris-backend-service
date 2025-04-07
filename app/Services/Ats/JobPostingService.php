<?php

namespace App\Services\Ats;

use App\Http\Requests\Job\JobPostRequest;
use App\Http\Resources\JobPostResource;
use App\Models\JobPost;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Exception;

class JobPostingService
{
    /**
     * Create a new job post.
     *
     * @param JobPostRequest $request
     * @return JobPostResource|null
     */
    public function createJobPost(JobPostRequest $request): ?JobPostResource
    {
        try {
            $jobPost = JobPost::create($request->validated());
            return new JobPostResource($jobPost);
        } catch (Exception $e) {
            Log::error('Job posting creation failed', ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Retrieve all job posts.
     *
     * @return Collection
     */
    public function getJobPosts(): Collection
    {
        try {
            return JobPostResource::collection(JobPost::all())->collection;
        } catch (Exception $e) {
            Log::error('Failed to retrieve job postings', ['error' => $e->getMessage()]);
            return collect(); // Return empty collection if error occurs
        }
    }

    /**
     * Update a specific job post.
     *
     * @param JobPostRequest $request
     * @param int $id
     * @return JobPostResource|null
     */
    public function updateJobPost(JobPostRequest $request, int $id): ?JobPostResource
    {
        try {
            $jobPost = JobPost::findOrFail($id);
            $jobPost->update($request->validated());
            return new JobPostResource($jobPost);
        } catch (Exception $e) {
            Log::error('Job posting update failed', ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Delete a job post by ID.
     *
     * @param int $id
     * @return bool
     */
    public function deleteJobPost(int $id): bool
    {
        try {
            $jobPost = JobPost::findOrFail($id);
            return $jobPost->delete();
        } catch (Exception $e) {
            Log::error('Job posting deletion failed', ['error' => $e->getMessage()]);
            return false;
        }
    }
}
