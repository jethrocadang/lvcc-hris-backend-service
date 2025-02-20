<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Requests\DepartmentRequest;
use App\Http\Resources\DepartmentResource;
use App\Models\Department;
use App\Http\Controllers\Controller;

class DepartmentController extends Controller
{
    public function createDepartment(DepartmentRequest $request){

        $department = Department::create($request->validated());

        return response()->json([
            'message' => 'Department created successfully!',
            'department' => new DepartmentResource($department)
        ], 201);
    }

    public function getDepartments(){

        $department = Department::all();
        return response()->json(['departments' => DepartmentResource::collection($department)], 200);
    }

    public function updateDepartment(DepartmentRequest $request, $id){

        //find department by id
        $department = Department::findOrFail($id);

        //update the chosen department
        $department->update($request->validated());

        return response()->json([
            'message' => 'Department updated successfully!',
            'department' => new DepartmentResource($department)
        ], 200);
    }

    public function deleteDepartment($id){

        $department = Department::findOrFail($id);
        $department->delete();

        return response()->json(['message' => 'Department deleted successfully!'], 200);
    }
}
