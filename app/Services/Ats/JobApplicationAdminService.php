<?php

namespace App\Services\Ats;

use App\Models\JobApplication;
use App\Http\Resources\JobApplicationWithInfoResource;
use Exception;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class JobApplicationAdminService
{
    public function getAllJobApplication(Request $request): AnonymousResourceCollection
    {
        $applications = QueryBuilder::for(JobApplication::class)
            ->with([
                'jobApplicant',
                'jobSelectionOptions.jobPost',
                'jobApplicationProgress.phase',
                'jobInterviewScheduling'
            ])
            ->when(
                $request->filled('filter.phase_status') && $request->filled('filter.phase_title'),
                function ($query) use ($request) {
                    $statuses = (array) data_get($request->query(), 'filter.phase_status', []);
                    $phaseTitle = data_get($request->query(), 'filter.phase_title');

                    $query->whereHas('jobApplicationProgress', function (Builder $query) use ($statuses, $phaseTitle) {
                        $query->whereIn('status', $statuses)
                            ->whereHas('phase', function (Builder $query) use ($phaseTitle) {
                                $query->where('title', $phaseTitle);
                            });
                    });
                }
            )
            ->when($request->filled('filter.job_category'), function ($query) use ($request) {
                $query->whereHas('jobSelectionOptions.jobPost', function (Builder $query) use ($request) {
                    $query->where('category', data_get($request->query(), 'filter.job_category'));
                });
            })
            ->when($request->filled('filter.email'), function ($query) use ($request) {
                $email = data_get($request->query(), 'filter.email');
                $query->whereHas('jobApplicant', function (Builder $query) use ($email) {
                    $query->where('email', 'LIKE', "%{$email}%");
                });
            })
            ->defaultSort('-created_at')
            ->paginate($request->query('per_page', 15))
            ->appends($request->query());

        return JobApplicationWithInfoResource::collection($applications);
    }


    public function getJobApplication(int $id)
    {
        $jobApplication = JobApplication::with([
            'jobApplicant.jobApplicantInformation',
            'jobSelectionOptions.jobPost',
            'jobApplicationProgress.phase',
            'jobInterviewScheduling'
        ])->findOrFail($id);

        return new JobApplicationWithInfoResource($jobApplication);
    }


    public function getAllJobApplicationBySlug(string $slug, Request $request)
    {
        try {
            $applications = JobApplication::with([
                'jobApplicant.jobApplicantInformation',
                'jobSelectionOptions.jobPost',
                'jobApplicationProgress.phase',
                'jobInterviewScheduling',
                'currentProgress.phase',
            ])
                ->when($request->filled('filter.job_category'), function ($query) use ($request) {
                    $query->whereHas('jobSelectionOptions.jobPost', function ($query) use ($request) {
                        $query->where('category', data_get($request->query(), 'filter.job_category'));
                    });
                })
                ->when($request->filled('filter.email'), function ($query) use ($request) {
                    $email = data_get($request->query(), 'filter.email');
                    $query->whereHas('jobApplicant', function ($query) use ($email) {
                        $query->where('email', 'LIKE', "%{$email}%");
                    });
                })
                ->when($request->filled('filter.full_name'), function ($query) use ($request) {
                    $fullName = data_get($request->query(), 'filter.full_name');
                    $query->whereHas('jobApplicant', function ($query) use ($fullName) {
                        $query->whereRaw(
                            "CONCAT_WS(' ', first_name, middle_name, last_name) LIKE ?",
                            ["%{$fullName}%"]
                        );
                    });
                })
                ->latest()
                ->get();

            $filtered = $applications->filter(function ($application) use ($slug) {
                $latestProgress = $application->jobApplicationProgress
                    ->sortByDesc('created_at')
                    ->first();

                return $latestProgress && $latestProgress->phase?->slug === $slug;
            })->values();

            $perPage = $request->query('per_page', 15);
            $page = $request->query('page', 1);
            $paginated = new LengthAwarePaginator(
                $filtered->forPage($page, $perPage),
                $filtered->count(),
                $perPage,
                $page,
                ['path' => $request->url(), 'query' => $request->query()]
            );

            return JobApplicationWithInfoResource::collection($paginated);
        } catch (Exception $e) {
            throw $e;
        }
    }
}
