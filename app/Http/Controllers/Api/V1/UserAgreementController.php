<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserAgreementRequest;
use App\Http\Resources\UserAgreementResource;
use App\Models\UserAgreement;
use Illuminate\Http\Request;

class UserAgreementController extends Controller
{
    public function createUserAgreement(UserAgreementRequest $request)
    {
        $userAgreement = UserAgreement::create($request->validated());

        return response()->json([
            'message' => 'User Agreement created successfully!',
            'user_agreements' => new UserAgreementResource($userAgreement)
        ], 201);
    }

    public function getUserAgreements()
    {
        $userAgreement = UserAgreement::all();
        return response()->json(['user_agreements' => UserAgreementResource::collection($userAgreement)], 200);
    }
}
