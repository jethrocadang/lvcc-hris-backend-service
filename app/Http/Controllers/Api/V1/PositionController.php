<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Requests\PositionRequest;
use App\Http\Resources\PositionResource;
use App\Models\Position;
use App\Http\Controllers\Controller;

class PositionController extends Controller
{
    public function createPosition(PositionRequest $request){

        $position = Position::create($request->validated());

        return response()->json([
            'message' => 'Position created successfully!',
            'position' => new PositionResource($position)
        ], 201);
    }
    public function getPositions(){

        $position = Position::all();
        return response()->json(['positions' => PositionResource::collection($position)], 200);
    }
    public function updatePosition(PositionRequest $request, $id){

        //find position by id
        $position = Position::findOrFail($id);

        //update the chosen position
        $position->update($request->validated());

        return response()->json([
            'message' => 'Position updated successfully!',
            'position' => new PositionResource($position)
        ], 200);
    }
    public function deletePosition($id){

        $position = Position::findOrFail($id);
        $position->delete();
        
        return response()->json(['message' => 'Position deleted successfully!'], 200);
    }
}
