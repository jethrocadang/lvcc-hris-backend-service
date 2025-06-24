<?php

namespace App\Services\Eth;

use App\Http\Requests\Eth\ExternalTrainingAttendanceRequest;
use App\Http\Resources\Eth\ExternalTrainingAttendanceResource;
use App\Models\ExternalTrainingAttendance;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Collection;

class ExternalTrainingAttendanceService
{
    public function getExternalTrainingAttendances()
    {
        $externalTrainingAttendance = ExternalTrainingAttendance::all();

        return $externalTrainingAttendance->isNotEmpty()
            ? ExternalTrainingAttendanceResource::collection($externalTrainingAttendance)->collection
            : collect();
    }

    public function getExternalTrainingAttendanceByEmployeeId(int $employeeId): Collection
    {
        try {
            $externalTrainingAttendances = ExternalTrainingAttendance::where('employee_id', $employeeId)
                ->orderBy('date_completed', 'desc')
                ->get();

            return $externalTrainingAttendances->isNotEmpty()
                ? ExternalTrainingAttendanceResource::collection($externalTrainingAttendances)->collection
                : collect();
        } catch (Exception $e) {
            Log::error('Failed to retrieve external training attendance by employee ID', [
                'employee_id' => $employeeId,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
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

    public function updateExternalTrainingAttendance(int $id, ExternalTrainingAttendanceRequest $request): ExternalTrainingAttendanceResource
    {
        try {
            $externalTrainingAttendance = ExternalTrainingAttendance::findOrFail($id);

            $data = $request->validated();

            if ($request->hasFile('certificate_url')) {
                // Delete the old certificate file if it exists
                if ($externalTrainingAttendance->certificate_url) {
                    Storage::disk('public')->delete($externalTrainingAttendance->certificate_url);
                }

                $path = $request->file('certificate_url')->store('external-certs', 'public');
                $data['certificate_url'] = $path;
            } else {
                // If no new file is provided, remove certificate_url from the data to prevent overwriting
                unset($data['certificate_url']);
            }

            $externalTrainingAttendance->update($data);

            return new ExternalTrainingAttendanceResource($externalTrainingAttendance);
        } catch (ModelNotFoundException $e) {
            Log::error('External training attendance not found.', ['error' => $e->getMessage()]);
            throw $e;
        } catch (Exception $e) {
            Log::error('External training attendance update failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function deleteExternalTrainingAttendance(int $id): bool
    {
        try {
            $externalTrainingAttendance = ExternalTrainingAttendance::findOrFail($id);

            // Delete the certificate file if it exists
            if ($externalTrainingAttendance->certificate_url) {
                Storage::disk('public')->delete($externalTrainingAttendance->certificate_url);
            }

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
