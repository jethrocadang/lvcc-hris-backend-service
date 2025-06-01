<?php

namespace App\Services\Hris;

use App\Http\Requests\InterviewScheduleSlotRequest;
use App\Http\Resources\InterviewScheduleSlotResource;
use App\Models\InterviewScheduleSlot;
use App\Models\InterviewScheduleTimeSlot;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InterviewScheduleSlotService
{
    public function getInterviewScheduleSlots(): Collection
    {
        $slots = InterviewScheduleSlot::with('timeSlots')->get();

        return $slots->isNotEmpty()
            ? InterviewScheduleSlotResource::collection($slots)->collection
            : collect();
    }

    public function getInterviewScheduleSlotById(int $id): InterviewScheduleSlotResource
    {
        try {
            $slot = InterviewScheduleSlot::with('timeSlots')->findOrFail($id);

            return new InterviewScheduleSlotResource($slot);
        } catch (ModelNotFoundException $e) {
            Log::error('Interview schedule slot not found', ['error' => $e->getMessage()]);
            throw $e;
        } catch (Exception $e) {
            Log::error('Interview schedule slot retrieval failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function createInterviewScheduleSlot(InterviewScheduleSlotRequest $request): InterviewScheduleSlotResource
    {
        DB::beginTransaction();
        try {
            $admin = auth('api')->user();

            $validated = $request->validated();
            $timeSlots = $validated['timeSlots'] ?? [];

            $scheduleSlot = InterviewScheduleSlot::create([
                'admin' => $admin->id,
                'scheduled_date' => $validated['scheduled_date'],
            ]);

            foreach ($timeSlots as $slot) {
                $scheduleSlot->timeSlots()->create([
                    'start_time' => $slot['start_time'],
                    'is_available' => true,
                ]);
            }

            DB::commit();

            return new InterviewScheduleSlotResource($scheduleSlot->load('timeSlots'));
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Interview schedule slot creation failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function updateInterviewScheduleSlot(InterviewScheduleSlotRequest $request, int $id): InterviewScheduleSlotResource
    {
        DB::beginTransaction();
        try {
            $scheduleSlot = InterviewScheduleSlot::findOrFail($id);

            $validated = $request->validated();
            $timeSlots = $validated['timeSlots'] ?? [];

            $scheduleSlot->update([
                'scheduled_date' => $validated['scheduled_date'],
            ]);

            // Optional strategy: delete and recreate time slots
            $scheduleSlot->timeSlots()->delete();
            foreach ($timeSlots as $slot) {
                $scheduleSlot->timeSlots()->create([
                    'start_time' => $slot['start_time'],
                    'is_available' => $slot['is_available'],
                ]);
            }

            DB::commit();

            return new InterviewScheduleSlotResource($scheduleSlot->load('timeSlots'));
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            Log::error('Interview schedule slot not found', ['error' => $e->getMessage()]);
            throw $e;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Interview schedule slot update failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function deleteInterviewScheduleSlot(int $id): bool
    {
        try {
            $slot = InterviewScheduleSlot::findOrFail($id);
            return $slot->delete();
        } catch (ModelNotFoundException $e) {
            Log::error('Interview schedule slot not found', ['error' => $e->getMessage()]);
            throw $e;
        } catch (Exception $e) {
            Log::error('Interview schedule slot deletion failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }
}
