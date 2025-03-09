<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\Auth\GoogleAuthService;
use App\Services\Auth\JwtService;
use App\Services\Auth\LoginService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    use ApiResponse;

    private GoogleAuthService $googleAuthService;
    private JwtService $jwtService;
    private LoginService $loginService;

    public function __construct(
        GoogleAuthService $googleAuthService,
        JwtService $jwtService,
        LoginService $loginService
    ) {
        $this->googleAuthService = $googleAuthService;
        $this->jwtService = $jwtService;
        $this->loginService = $loginService;
    }

    public function googleAuthentication(Request $request)
    {
        try {
            $validatedData = $request->validate(['code' => 'required|string']);
            $user = $this->googleAuthService->authenticate($validatedData['code']);
            
            return $this->successResponse('Authentication successful', $this->jwtService->generateTokens($user));
        } catch (\Exception $e) {
            Log::error('Google authentication failed', ['error' => $e->getMessage()]);
            return $this->errorResponse('Google authentication failed', [], 500);
        }
    }

    public function refreshToken(Request $request)
    {
        return $this->jwtService->refreshToken($request);
    }

    public function login(Request $request)
    {
        return $this->loginService->login($request);
    }
}
