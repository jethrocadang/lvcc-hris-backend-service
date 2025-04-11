<?php

namespace App\Http\Controllers\Api\V1\Ats;

use App\Http\Controllers\Controller;
use App\Http\Requests\TokenRequest;
use App\Models\JobApplication;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class PortalAuthController extends Controller
{
    use ApiResponse;
    public function authenticate(TokenRequest $request)
    {
        
        $application = JobApplication::where('portal_token', $request->validated())->firstOrFail();

        if(!$application){
            return $this->errorResponse('Invalid token!',[], 401);
        }

        return $this->successResponse('Authentication Successful', $this->generateTokens($application));

    }

    private function generateTokens(JobApplication $application): array
    {
        return [
            'job_applicant' => $application,
            'access_token' => JWTAuth::fromUser($application),
            'token_type' => 'Bearer'
        ];
    }

}
