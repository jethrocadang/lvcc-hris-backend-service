<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\PolicyRequest;
use App\Http\Resources\PolicyResource;
use App\Models\Position;
use App\Models\Policy;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PolicyController extends Controller
{
    public function createPolicy(PolicyRequest $request)
    {
        $policy = Policy::create($request->validated());

        return response()->json([
            'message' => 'Policy created successfully!',
            'policy' => new PolicyResource($policy)
        ], 201);
    }

    public function getPolicies()
    {
        $policy = Policy::all();
        return response()->json(['policies' => PolicyResource::collection($policy)], 200);
    }

    public function updatePolicy(PolicyRequest $request, $id)
    {
        //find policy by id
        $policy = Policy::findOrFail($id);

        //update the chosen policy
        $policy->update($request->validated());

        return response()->json([
            'message' => 'Policy updated successfully!',
            'policy' => new PolicyResource($policy)
        ], 200);
    }

    public function deletePolicy($id)
    {
        $policy = Policy::findOrFail($id);
        $policy->delete();

        return response()->json(['message' => 'Policy deleted successfully!'], 200);
    }
}
