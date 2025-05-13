<?php

namespace App\Services\Ats;

use App\Http\Requests\Ats\JobPostRequest;
use App\Http\Resources\JobPostResource;
use App\Models\JobPost;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

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
    public function getJobPosts(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        try {
            return QueryBuilder::for(JobPost::class)
                ->allowedFilters([
                    AllowedFilter::partial('title'),
                    AllowedFilter::exact('status'),
                    AllowedFilter::exact('work_type'),
                    AllowedFilter::exact('job_type'),
                ])
                ->allowedSorts(['created_at', 'title'])
                ->paginate($perPage)
                ->appends($filters);
        } catch (Exception $e) {
            Log::error('Failed to retrieve job postings', ['error' => $e->getMessage()]);
            return new LengthAwarePaginator([], 0, $perPage);
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

    public function getJobPostById(int $id)
    {
        try {
            $jobPost = JobPost::findOrFail($id);

            return new JobPostResource($jobPost);
        } catch (ModelNotFoundException $e) {
            Log::error('Job Post not found', ['error' => $e->getMessage()]);
            throw $e;
        } catch (Exception $e) {
            Log::error('Job Post retrieval failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }
}
