<?php

namespace App\Services\Ats;

use App\Http\Requests\JobInterviewSchedulingRequest;
use Illuminate\Support\Facades\Log;
use App\Models\JobInterviewScheduling;
use App\Models\InterviewScheduleSlot;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Exception;

class JobInterviewSchedulingService
{


    public function createSchedule(JobInterviewSchedulingRequest $request): string
    {
        try {
            // Get the authenticated Job Applicant
            $jobApplication = auth('ats')->user();

            $request->validated();

            $slot = InterviewScheduleSlot::where('id', $request->interview_schedule_slot_id)
                ->where('slot_status', 'available')
                ->first();

            if (!$slot) {
                throw new Exception('Interview Schedule Slot is not Available', 401);
            }

            // Prevent applicant from scheduling multiple times
            $existingSchedule = JobInterviewScheduling::where('job_application_id', $jobApplication->id)->first();

            if ($existingSchedule) {
               throw new Exception('Interview schedule already exists', 401);
            }

            DB::transaction(function () use ($request, $jobApplication, $slot) {
                // Store schedule in tenant DB
                JobInterviewScheduling::create([
                    'job_application_id' => $jobApplication->id,
                    'interview_schedule_slot_id' => $slot->id,
                    'selected_date' => $request->selected_date,
                    'selected_time' => $request->selected_time,
                    'schedule_status' => $request->schedule_status,
                ]);
                // Mark slot as booked in landlord DB
                $slot->update([
                    'slot_status' => 'booked'
                ]);
            });

            return "Interview scheduled successfully";
        } catch (Exception $e) {
            Log::error('[Interview Scheduling] Error: ' . $e->getMessage());
            throw $e;
        }
    }
}
