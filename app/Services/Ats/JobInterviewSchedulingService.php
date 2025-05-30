<?php

namespace App\Services\Ats;

use App\Http\Requests\JobInterviewSchedulingRequest;
use Illuminate\Support\Facades\Log;
use App\Models\JobInterviewScheduling;
use App\Models\InterviewScheduleSlot;
use App\Models\InterviewScheduleTimeSlot;
use App\Models\JobApplicationProgress;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class JobInterviewSchedulingService
{


    public function createScheduleByApplicant(JobInterviewSchedulingRequest $request): string
    {
        try {
            // Get the authenticated Job Applicant
            $jobApplication = auth('ats')->user();

            $validated = $request->validated();

            // Check slot existence in landlord DB
            $slot = InterviewScheduleSlot::findOrFail($validated['interview_slot_id']);

            // Check time slot availability
            $timeSlot = InterviewScheduleTimeSlot::where('id', $validated['interview_time_slot_id'])
                ->where('interview_slot_id', $slot->id)
                ->where('available', true)
                ->first();

            if (!$timeSlot) {
                throw new Exception('The selected time slot is no longer available.', 401);
            }

            // Prevent duplicate scheduling for the same applicant and phase
            $existingSchedule = JobInterviewScheduling::where('job_application_id', $jobApplication->id)
                ->where('job_application_phase_id', $validated['job_application_phase_id'])
                ->first();

            if ($existingSchedule) {
                throw new Exception('You have already scheduled an interview for this phase.', 401);
            }

            DB::transaction(function () use ($jobApplication, $validated, $slot, $timeSlot) {
                // Store interview schedule in tenant DB
                JobInterviewScheduling::create([
                    'job_application_id' => $jobApplication->id,
                    'job_application_phase_id' => $validated['job_application_phase_id'],
                    'interview_slot_id' => $slot->id,
                    'interview_time_slot_id' => $timeSlot->id,
                    'selected_date' => $validated['selected_date'],
                    'selected_time' => $validated['selected_time'],
                    'schedule_status' => 'booked',
                ]);

                // Mark the selected time slot as unavailable in landlord DB
                $timeSlot->update(['available' => false]);

                $progress = JobApplicationProgress::where('job_application_id', $validated['job_application_id'])
                    ->where('job_application_phase_id', $validated['job_application_phase_id'])
                    ->firstOrFail();

                $progress->update([
                    'status' => 'pending',
                ]);
            });

            return "Interview scheduled successfully.";
        } catch (Exception $e) {
            Log::error('[Interview Scheduling] Error: ' . $e->getMessage());
            throw $e;
        }
    }


    public function createScheduleByAdmin(JobInterviewSchedulingRequest $request): string
    {
        try {
            // Get the authenticated Job Applicant
            $user = auth('ats')->user();

            $validated = $request->validated();

            $jobApplication = $validated['job_application_id'];

            // Prevent duplicate scheduling for the same applicant and phase
            $existingSchedule = JobInterviewScheduling::where('job_application_id', $jobApplication)
                ->where('job_application_phase_id', $validated['job_application_phase_id'])
                ->first();

            if ($existingSchedule) {
                throw new Exception('You have already scheduled an interview for this phase.', 401);
            }

            DB::transaction(function () use ($jobApplication, $validated) {
                // Store interview schedule in tenant DB
                JobInterviewScheduling::create([
                    'job_application_id' => $jobApplication,
                    'job_application_phase_id' => $validated['job_application_phase_id'],
                    'selected_date' => $validated['selected_date'],
                    'selected_time' => $validated['selected_time'],
                    'schedule_status' => 'booked',
                    'location' => $validated['location'],
                    'what_to_bring' => $validated['what_to_bring']
                ]);

                $progress = JobApplicationProgress::where('job_application_id', $validated['job_application_id'])
                    ->where('job_application_phase_id', $validated['job_application_phase_id'])
                    ->firstOrFail();

                $progress->update([
                    'status' => 'accepted',
                ]);
            });

            return "Interview scheduled successfully.";
        } catch (Exception $e) {
            Log::error('[Interview Scheduling] Error: ' . $e->getMessage());
            throw $e;
        }
    }

    public function getInterviewSchedules(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        try {
            return QueryBuilder::for(JobInterviewScheduling::with([
                'user',
                'jobApplication.jobApplicant',
                'jobApplicationPhase',
            ]))
                ->allowedFilters([
                    AllowedFilter::exact('user_id'),
                    AllowedFilter::exact('job_application_id'),
                    AllowedFilter::exact('interview_slot_id'),
                    AllowedFilter::exact('interview_time_slot_id'),
                    AllowedFilter::exact('job_application_phase_id'),
                    AllowedFilter::exact('schedule_status'),
                    AllowedFilter::partial('location'),
                ])
                ->allowedSorts(['selected_date', 'selected_time', 'created_at'])
                ->paginate($perPage)
                ->appends($filters);
        } catch (Exception $e) {
            Log::error('Failed to retrieve interview schedules', ['error' => $e->getMessage()]);
            return new LengthAwarePaginator([], 0, $perPage);
        }
    }

    public function getInterviewSchedulesByJobApplication(int $jobApplicationId)
    {
        try {
            return JobInterviewScheduling::with([
                'user',
                'jobApplication.jobApplicant',
                'jobApplicationPhase',
            ])
                ->where('job_application_id', $jobApplicationId)
                ->get();
        } catch (Exception $e) {
            Log::error('Failed to retrieve interview schedules by job application', [
                'job_application_id' => $jobApplicationId,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }
}
