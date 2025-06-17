<?php

namespace App\Services\Ats;

use App\Http\Requests\Ats\JobPostRequest;
use App\Http\Resources\JobPostResource;
use App\Models\JobPost;
use App\Models\Department;
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
            // Validate that the department exists in the landlord database
            if ($request->has('department_id')) {
                $departmentId = $request->input('department_id');
                // Check if department exists
                $department = Department::find($departmentId);
                if (!$department) {
                    throw new ModelNotFoundException("Department with ID {$departmentId} not found");
                }
            }

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
            $query = QueryBuilder::for(JobPost::class)
                ->allowedFilters([
                    AllowedFilter::partial('title'),
                    AllowedFilter::exact('status'),
                    AllowedFilter::exact('work_type'),
                    AllowedFilter::exact('job_type'),
                    AllowedFilter::exact('department_id'),
                ])
                ->allowedSorts(['created_at', 'title']);

            // Explicitly load department relationship
            try {
                // Try to load department relationship
                $jobPosts = $query->paginate($perPage)->appends($filters);
                foreach ($jobPosts as $jobPost) {
                    try {
                        $jobPost->load('department');
                    } catch (\Exception $e) {
                        \Log::warning("Failed to load department for job post {$jobPost->id}: {$e->getMessage()}");
                    }
                }
                return $jobPosts;
            } catch (\Exception $e) {
                \Log::warning("Failed to load departments: {$e->getMessage()}");
                return $query->paginate($perPage)->appends($filters);
            }
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
            // Validate that the department exists in the landlord database
            if ($request->has('department_id')) {
                $departmentId = $request->input('department_id');
                // Check if department exists
                $department = Department::find($departmentId);
                if (!$department) {
                    throw new ModelNotFoundException("Department with ID {$departmentId} not found");
                }
            }

            $jobPost = JobPost::findOrFail($id);
            $jobPost->update($request->validated());
            return new JobPostResource($jobPost->fresh('department')); // Reload with department
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

    /**
     * Get a job post by ID with department information.
     *
     * @param int $id
     * @return JobPostResource
     */
    public function getJobPostById(int $id)
    {
        try {
            $jobPost = JobPost::findOrFail($id);

            // Try to load the department relationship if it exists
            try {
                $departmentId = $jobPost->department_id;
                if ($departmentId) {
                    $department = \App\Models\Department::find($departmentId);
                    if ($department) {
                        // Force load the department relationship
                        $jobPost->setRelation('department', $department);
                    }
                }
            } catch (Exception $e) {
                Log::warning('Failed to load department relationship', ['error' => $e->getMessage()]);
                // Continue without the department relationship
            }

            return new JobPostResource($jobPost);
        } catch (ModelNotFoundException $e) {
            Log::error('Job Post not found', ['error' => $e->getMessage()]);
            throw $e;
        } catch (Exception $e) {
            Log::error('Job Post retrieval failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Get all job posts for a specific department.
     *
     * @param int $departmentId
     * @param array $filters
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getJobPostsByDepartment(int $departmentId, array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        try {
            // Verify department exists
            $department = Department::findOrFail($departmentId);

            $query = QueryBuilder::for(JobPost::class)
                ->where('department_id', $departmentId)
                ->allowedFilters([
                    AllowedFilter::partial('title'),
                    AllowedFilter::exact('status'),
                    AllowedFilter::exact('work_type'),
                    AllowedFilter::exact('job_type'),
                ])
                ->allowedSorts(['created_at', 'title']);

            // Get paginated results
            $jobPosts = $query->paginate($perPage)->appends($filters);

            // Manually set the department relation for each job post
            foreach ($jobPosts as $jobPost) {
                try {
                    // Force set the department relation to ensure it's available
                    $jobPost->setRelation('department', $department);
                } catch (\Exception $e) {
                    Log::warning("Failed to set department relation for job post {$jobPost->id}", ['error' => $e->getMessage()]);
                }
            }

            return $jobPosts;
        } catch (Exception $e) {
            Log::error('Failed to retrieve job postings by department', ['error' => $e->getMessage()]);
            return new LengthAwarePaginator([], 0, $perPage);
        }
    }
}
