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
}
