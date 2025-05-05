<?php

namespace App\Http\Controllers\Api\V1\Ats;

use App\Http\Controllers\Controller;
use App\Http\Requests\TokenRequest;
use App\Models\JobApplication;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
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
        return $this->generateTokens($application);

    }

    /**
     * Generates a JWT access token for the applicant.
     *
     * @param JobApplication $application
     * @return array
     */
    public function generateTokens(JobApplication $application): JsonResponse
    {
        // Generate access token with default TTL
        $accessToken = JWTAuth::claims(['type' => 'access'])->fromUser($application);

        // Temporarily set a longer TTL for refresh token
        $refreshTTL = 60 * 24 * 7; // 7 days
        $factory = JWTAuth::factory();
        $defaultTTL = $factory->getTTL(); // Save current TTL
        $factory->setTTL($refreshTTL);

        // Generate refresh token
        $refreshToken = JWTAuth::claims(['type' => 'refresh'])->fromUser($application);

        // Reset TTL to default (optional)
        $factory->setTTL($defaultTTL);

        return response()->json([
            'job_applicant' => $application,
            'access_token' => $accessToken,
            'token_type' => 'Bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60,
        ])->cookie(
            'refresh_token',
            $refreshToken,
            $refreshTTL,   // in minutes
            '/',           // path
            null,          // domain
            true,          // secure
            true,          // httpOnly
            false,         // raw
            'Strict'       // SameSite
        );
    }

}
