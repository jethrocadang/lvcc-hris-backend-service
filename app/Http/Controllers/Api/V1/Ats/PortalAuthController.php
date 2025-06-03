<?php

namespace App\Http\Controllers\Api\V1\Ats;

use App\Http\Controllers\Controller;
use App\Http\Requests\TokenRequest;
use App\Http\Resources\JobApplicationResource;
use App\Models\JobApplication;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Log;
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
        Log::info("🔑 AUTH REQUEST with token: " . $request->token);

        $token = $request->validated();
        // Find the applicant using the validated portal token
        $application = JobApplication::where('portal_token', $token['token'])->firstOrFail();

        // Token is valid; return JWT and applicant data
        return $this->generateTokens($application);
    }

    public function refreshToken(Request $request)
    {
        try {
            // Check the cookie for the request
            $refreshToken = $request->cookie('refresh_token');
            if (!$refreshToken) {
                return response()->json(['error' => 'Refresh token not found'], 401);
            }

            // Decode the token using tymon JWTAuth
            $decoded = JWTAuth::setToken($refreshToken)->getPayload();
            if (!$decoded || $decoded['type'] !== 'refresh') {
                return response()->json(['error' => 'Invalid or expired refresh token'], 401);
            }

            // Find the user
            $applicant = JobApplication::find($decoded['sub']);
            if (!$applicant) {
                return response()->json(['error' => 'applicant not found'], 404);
            }
            return $this->generateTokens($applicant);
        } catch (Exception $e) {
            Log::error('Token refresh failed: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to refresh token'], 500);
        }
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
            'jobApplication' => new JobApplicationResource($application),
            'accessToken' => $accessToken,
            'tokenType' => 'Bearer',
            'expiresIn' => JWTAuth::factory()->getTTL() * 60,
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
