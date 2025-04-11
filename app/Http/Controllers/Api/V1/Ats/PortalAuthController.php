<?php

namespace App\Http\Controllers\Api\V1\Ats;

use App\Http\Controllers\Controller;
use App\Http\Requests\TokenRequest;
use App\Models\JobApplication;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

/**
 * Authenticates applicants accessing the portal using a secure token.
 */
class PortalAuthController extends Controller
{
    use ApiResponse;

    /**
     * Authenticates the applicant based on a one-time portal token.
     * Returns a JWT token on success for protected portal access.
     *
     * @param TokenRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function authenticate(TokenRequest $request)
    {
        $token = $request->validated();
        // Find the applicant using the validated portal token
        $application = JobApplication::where('portal_token', $token['token'])->firstOrFail();

        // Token is valid; return JWT and applicant data
        return $this->successResponse(
            'Authentication Successful',
            $this->generateTokens($application)
        );
    }

    /**
     * Generates a JWT access token for the applicant.
     *
     * @param JobApplication $application
     * @return array
     */
    private function generateTokens(JobApplication $application): array
    {
        return [
            'job_applicant' => $application,
            'access_token' => JWTAuth::fromUser($application),
            'token_type' => 'Bearer',
        ];
    }
}
