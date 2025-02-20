<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Models\UserPolicyAgreement;
use App\Http\Requests\UserPolicyAgreementRequest;
use App\Http\Resources\UserPolicyAgreementResource;
use App\Http\Controllers\Controller;

class UserPolicyAgreementController extends Controller
{
    public function createUserPolicyAgreement(UserPolicyAgreementRequest $request)
    {
        $userPolicyAgreement = UserPolicyAgreement::create($request->all());
        
        // Load relationships after creation
        $userPolicyAgreement->load(['policy', 'user_agreement']);

        return response()->json([
            'message' => 'User Agreement created successfully!',
            'user_agreements' => new UserPolicyAgreementResource($userPolicyAgreement)
        ], 201);
    }

    public function getUserPolicyAgreement()
    {
        $userPolicyAgreement = UserPolicyAgreement::with(['policy', 'user_agreement'])->get();
        return response()->json([
            'user_agreements' => UserPolicyAgreementResource::collection($userPolicyAgreement)
        ], 200);
    }

    public function updateUserPolicyAgreement(UserPolicyAgreementRequest $request, $id)
    {
        $userPolicyAgreement = UserPolicyAgreement::findOrFail($id);
        $userPolicyAgreement->update($request->all());

        // Load relationships after update
        $userPolicyAgreement->load(['policy', 'user_agreement']);

        return response()->json([
            'message' => 'User Agreement updated successfully!',
            'user_agreements' => new UserPolicyAgreementResource($userPolicyAgreement)
        ], 200);
    }

    public function deleteUserPolicyAgreement($id)
    {
        $userPolicyAgreement = UserPolicyAgreement::findOrFail($id);
        $userPolicyAgreement->delete();

        return response()->json([
            'message' => 'User Agreement deleted successfully!'
        ], 200);
    }
}
