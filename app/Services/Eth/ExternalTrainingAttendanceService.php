<?php

namespace App\Services\Eth;

use App\Http\Requests\Eth\ExternalTrainingAttendanceRequest;
use App\Http\Resources\Eth\ExternalTrainingAttendanceResource;
use App\Models\ExternalTrainingAttendance;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

class ExternalTrainingAttendanceService
{
    public function getExternalTrainingAttendances()
    {
        $externalTrainingAttendance = ExternalTrainingAttendance::all();

        return $externalTrainingAttendance->isNotEmpty()
            ? ExternalTrainingAttendanceResource::collection($externalTrainingAttendance)->collection
            : collect();
    }
    public function getExternalTrainingAttendanceById(int $id)
    {
        try {
            $externalTrainingAttendance = ExternalTrainingAttendance::findOrFail($id);

            return new ExternalTrainingAttendanceResource($externalTrainingAttendance);
        } catch (ModelNotFoundException $e) {
            Log::error('External training attendance not found.', ['error' => $e->getMessage()]);
            throw $e;
        } catch (Exception $e) {
            Log::error('External training attendance retrieval failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }
    public function createExternalTrainingAttendance(ExternalTrainingAttendanceRequest $request): ExternalTrainingAttendanceResource
    {
        try {
            $employee = auth('api')->user();

            $data = $request->validated();
            $data['employee_id'] = $employee->id; // set the logged-in user as the author
            $data['date_started'] = now(); // set automatically to current date

             if ($request->hasFile('certificate_url')) {
                $path = $request->file('certificate_url')->store('external-certs', 'public');
                $data['certificate_url'] = $path; 
            }

            $externalTrainingAttendance = ExternalTrainingAttendance::create($data);

            return new ExternalTrainingAttendanceResource($externalTrainingAttendance);
        } catch (Exception $e) {
            Log::error('External training attendance creation failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }
    public function deleteExternalTrainingAttendance(int $id): bool
    {
        try {
            $externalTrainingAttendance = ExternalTrainingAttendance::findOrFail($id);
            return $externalTrainingAttendance->delete();
        } catch (ModelNotFoundException $e) {
            Log::error('External training attendance not found.', ['error' => $e->getMessage()]);
            throw $e;
        } catch (Exception $e) {
            Log::error('External training attendance deletion failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }
}