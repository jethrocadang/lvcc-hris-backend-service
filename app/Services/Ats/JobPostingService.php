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
            return QueryBuilder::for(JobPost::class)
                ->allowedFilters([
                    AllowedFilter::partial('title'),
                    AllowedFilter::exact('status'),
                    AllowedFilter::exact('work_type'),
                    AllowedFilter::exact('job_type'),
                    AllowedFilter::exact('department_id'),
                ])
                ->with('department') // Eager load the department relationship
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
            $jobPost = JobPost::with('department')->findOrFail($id);
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

            return QueryBuilder::for(JobPost::class)
                ->where('department_id', $departmentId)
                ->allowedFilters([
                    AllowedFilter::partial('title'),
                    AllowedFilter::exact('status'),
                    AllowedFilter::exact('work_type'),
                    AllowedFilter::exact('job_type'),
                ])
                ->with('department')
                ->allowedSorts(['created_at', 'title'])
                ->paginate($perPage)
                ->appends($filters);
        } catch (Exception $e) {
            Log::error('Failed to retrieve job postings by department', ['error' => $e->getMessage()]);
            return new LengthAwarePaginator([], 0, $perPage);
        }
    }
}
