<?php

namespace App\Services\Eth;

use App\Http\Requests\Eth\TrainingCourseModuleRequest;
use App\Http\Resources\Eth\TrainingCourseModuleResource;
use App\Models\TrainingCourseModule;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

class TrainingCourseModuleService
{
    public function getCourseModules()
    {
        $courseModule = TrainingCourseModule::all();

        return $courseModule->isNotEmpty()
        ? TrainingCourseModuleResource::collection($courseModule)->collection
        : collect();
    }

    public function getCourseModuleById(int $id)
    {
        try{

            $courseModule = TrainingCourseModule::findOrFail($id);

            return new TrainingCourseModuleResource($courseModule);
        } catch(ModelNotFoundException $e) {
            Log::error('Module not found.',['error' => $e->getMessage()]);
            throw $e;
        } catch(Exception $e){
            Log::error('Module retrieval failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function createCourseModule(TrainingCourseModuleRequest $request): TrainingCourseModuleResource
    {
        try{

            $data = $request->validated();

            if ($request->hasFile('file_content')) {
                $path = $request->file('file_content')->store('modules', 'public');
                $data['file_content'] = $path; // This will be saved in the DB
            }

            if (request()->hasFile('image_content')) {
                $path = request()->file('image_content')->store('modules', 'public');
                $data['image_content'] = $path; // This will be saved in the DB
            }

            $courseModule = TrainingCourseModule::create($data);

            return new TrainingCourseModuleResource($courseModule);
        }catch(Exception $e){
            Log::error('Module creation failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function updateCourseModule(TrainingCourseModuleRequest $request, int $id): TrainingCourseModuleResource
    {
        try {
            $courseModule = TrainingCourseModule::findOrFail($id);

            $data = $request->validated();

            if ($request->hasFile('file_content')) {
                $path = $request->file('file_content')->store('modules', 'public');
                $data['file_content'] = $path; // This will be saved in the DB
            }

            if ($request->hasFile('image_content')) {
                $path = request()->file('image_content')->store('modules', 'public');
                $data['image_content'] = $path; // This will be saved in the DB
            }

            $courseModule->update($data);

            return new TrainingCourseModuleResource($courseModule);
        } catch (Exception $e) {
            Log::error('Module update failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function deleteCourseModule(int $id): bool
    {
        try {
            $courseModule = TrainingCourseModule::findOrFail($id);
            return $courseModule->delete();
        } catch (Exception $e) {
            Log::error('Module deletion failed', ['error' => $e->getMessage()]);
            return false;
        }
    }
}