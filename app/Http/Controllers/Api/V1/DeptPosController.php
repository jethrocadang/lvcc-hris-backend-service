<?php
namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Requests\DeptPosRequest;
use App\Http\Resources\DeptPosResource;
use App\Models\DepartmentPosition;
use App\Http\Controllers\Controller;


class DeptPosController extends Controller
{
    public function createDeptPos(DeptPosRequest $request){

        $deptPos = DepartmentPosition::create($request->validated());
        
        $deptPos->load(['department', 'position']);

        return response()->json([
            'message' => 'Department Position created successfully!',
            'department_position' => new DeptPosResource($deptPos)
        ], 201);
    }

    public function getDeptPos(){

        $deptPos = DepartmentPosition::with(['department', 'position'])->get();

        return response()->json(['department_positions' => DeptPosResource::collection($deptPos)], 200);
    }

    public function updateDeptPos(DeptPosRequest $request, $id){

        //find department position by id
        $deptPos = DepartmentPosition::findOrFail($id);
        $deptPos->update($request->all());

        $deptPos->load(['department', 'position']);

        //update the chosen department position
        $deptPos->update($request->validated());

        return response()->json([
            'message' => 'Department Position updated successfully!',
            'department_position' => new DeptPosResource($deptPos)
        ], 200);
    }

    public function deleteDeptPos($id){

        $deptPos = DepartmentPosition::findOrFail($id);
        $deptPos->delete();

        return response()->json(['message' => 'Department Position deleted successfully!'], 200);
    }
}
