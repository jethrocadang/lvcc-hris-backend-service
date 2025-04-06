<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\Auth\GoogleAuthService;
use App\Services\Auth\JwtService;
use App\Services\Auth\LoginService;
use App\Services\Auth\UpdatePasswordService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Auth\UpdatePasswordRequest;

/**
 * AuthController handles user authentication-related operations,
 * including login, Google OAuth authentication, and token refreshing.
 *
 * This acts as the primary gateway of native users of the HRIS
 *
 * @method googleAuthentication(Request $request) authenticate user via google Oauth
 * @method refreshToken(Request $request) refresh tokens
 * @method login(Request $request)authenticate users via credentials
 */
class AuthController extends Controller
{
    use ApiResponse; // Uses the ApiResponse trait to standardize API responses.

    private GoogleAuthService $googleAuthService;
    private JwtService $jwtService;
    private LoginService $loginService;
    private UpdatePasswordService $updatePasswordService;

    /**
     * Inject dependencies into the AuthController.
     *
     * @param GoogleAuthService $googleAuthService Handles Google authentication.
     * @param JwtService $jwtService Handles JWT token generation and refreshing.
     * @param LoginService $loginService Handles standard login authentication.
     * @param UpdatePasswordService $updatePasswordService  handles update password.
     */
    public function __construct(
        GoogleAuthService $googleAuthService,
        JwtService $jwtService,
        LoginService $loginService,
        UpdatePasswordService $updatePasswordService,
    ) {
        $this->googleAuthService = $googleAuthService;
        $this->jwtService = $jwtService;
        $this->loginService = $loginService;
        $this->updatePasswordService = $updatePasswordService;
    }

    /**
     * Authenticates a user using Google OAuth.
     *
     * @param Request $request The request containing the Google authorization code.
     * @return \Illuminate\Http\JsonResponse Returns the authenticated user with JWT tokens.
     */
    public function googleAuthentication(Request $request)
    {
        try {
            // Validate that the request includes a 'code' parameter.
            $validatedData = $request->validate(['code' => 'required|string']);

            // Authenticate user using Google OAuth and retrieve user information.
            $user = $this->googleAuthService->authenticate($validatedData['code']);

            // Generate JWT tokens for the authenticated user.
            return $this->successResponse('Authentication successful', $this->jwtService->generateTokens($user));
        } catch (\Exception $e) {
            // Log authentication failure and return an error response.
            Log::error('Google authentication failed', ['error' => $e->getMessage()]);
            return $this->errorResponse('Google authentication failed', [], 500);
        }
    }

    /**
     * Refreshes an expired JWT access token using the refresh token.
     *
     * @param Request $request The request containing the refresh token.
     * @return \Illuminate\Http\JsonResponse Returns a new access token.
     */
    public function refreshToken(Request $request)
    {
        return $this->jwtService->refreshToken($request);
    }

    /**
     * Authenticates a user using email and password.
     *
     * @param Request $request The request containing user credentials.
     * @return \Illuminate\Http\JsonResponse Returns the authenticated user with JWT tokens.
     */
    public function login(Request $request)
    {
        // Validate incoming request data.
        $validatedData = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Attempt authentication with the provided credentials.
        if (!Auth::attempt($validatedData)) {
            return $this->errorResponse('Invalid credentials', [], 401);
        }

        // Retrieve the authenticated user.
        $user = Auth::user();

        // Generate and return JWT tokens.
        return $this->successResponse('Login successful', $this->jwtService->generateTokens($user));
    }

    public function updatePassword(UpdatePasswordRequest $request)
    {
        $result = $this->updatePasswordService->updatePassword(
            $request->email,
            $request->current_password,
            $request->new_password
        );

        return $result['success']
            ? $this->successResponse($result['message'], [])
            : $this->errorResponse($result['message'], [], 400);
    }

    //TODO add MFA
}
