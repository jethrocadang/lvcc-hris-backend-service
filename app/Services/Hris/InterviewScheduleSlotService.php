<?php
namespace App\Services\Hris;

use App\Http\Requests\InterviewScheduleSlotRequest;
use App\Http\Resources\InterviewScheduleSlotResource;
use App\Models\InterviewScheduleSlot;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class InterviewScheduleSlotService
{
    /**
     * Retrieve all interview schedule slots.
     *
     * @return Collection
     */
    public function getInterviewScheduleSlots(): Collection
    {
        // Make query
        $interviewScheduleSlots = InterviewScheduleSlot::all();

        // Return collection if not empty else return empty collection.
        return $interviewScheduleSlots->isNotEmpty()
            ? InterviewScheduleSlotResource::collection($interviewScheduleSlots)->collection
            : collect();
    }

    /**
     * Get interview schedule slot by ID.
     *
     * @param int $id
     * @return InterviewScheduleSlotResource
     * @throws ModelNotFoundException|Exception
     */
    public function getInterviewScheduleSlotById(int $id): InterviewScheduleSlotResource
    {
        try {
            // Find the interview schedule slot by ID
            $interviewScheduleSlot = InterviewScheduleSlot::findOrFail($id);

            // Return the interview schedule slot
            return new InterviewScheduleSlotResource($interviewScheduleSlot);
        } catch (ModelNotFoundException $e) {
            // Log error if not found
            Log::error('Interview schedule slot not found', ['error' => $e->getMessage()]);
            throw $e;
        } catch (Exception $e) {
            // Log errors and return the exception
            Log::error('Interview schedule slot retrieval failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }


    /**
     * Create a new interview schedule slot.
     *
     * @param InterviewScheduleSlotRequest $request
     * @return InterviewScheduleSlotResource
     * @throws Exception
     */
    public function createInterviewScheduleSlot(InterviewScheduleSlotRequest $request): InterviewScheduleSlotResource
    {
        try {
            // Validate then create new interview schedule slot
            $interviewScheduleSlot = InterviewScheduleSlot::create($request->validated());

            // Return created interview schedule slot
            return new InterviewScheduleSlotResource($interviewScheduleSlot);
        } catch (Exception $e) {
            // Log errors and return the exception
            Log::error('Interview schedule slot creation failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Update an interview schedule slot.
     *
     * @param InterviewScheduleSlotRequest $request
     * @param int $id
     * @return InterviewScheduleSlotResource
     * @throws ModelNotFoundException|Exception
     */

    public function updateInterviewScheduleSlot(InterviewScheduleSlotRequest $request, int $id): InterviewScheduleSlotResource
    {
        try {
            // Find the interview schedule slot by ID
            $interviewScheduleSlot = InterviewScheduleSlot::findOrFail($id);

            // Update the interview schedule slot with validated data
            $interviewScheduleSlot->update($request->validated());

            // Return updated interview schedule slot
            return new InterviewScheduleSlotResource($interviewScheduleSlot);
        } catch (ModelNotFoundException $e) {
            // Log error if not found
            Log::error('Interview schedule slot not found', ['error' => $e->getMessage()]);
            throw $e;
        } catch (Exception $e) {
            // Log errors and return the exception
            Log::error('Interview schedule slot update failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }
    /**
     * Delete an interview schedule slot.
     *
     * @param int $id
     * @return bool
     * @throws ModelNotFoundException|Exception
     */
    public function deleteInterviewScheduleSlot(int $id): bool
    {
        try {
            // Find the interview schedule slot by ID
            $interviewScheduleSlot = InterviewScheduleSlot::findOrFail($id);

            // Delete the interview schedule slot
            return $interviewScheduleSlot->delete();
        } catch (ModelNotFoundException $e) {
            // Log error if not found
            Log::error('Interview schedule slot not found', ['error' => $e->getMessage()]);
            throw $e;
        } catch (Exception $e) {
            // Log errors and return the exception
            Log::error('Interview schedule slot deletion failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }
}
