<?php

namespace App\Http\Controllers\Api\V1\Ats;

use App\Models\JobApplicant;
use App\Http\Controllers\Controller;
use App\Http\Requests\Ats\JobApplicationRequest;
use App\Http\Resources\JobApplicationResource;
use App\Services\Auth\JwtService;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

class JobApplicationController extends Controller
{
    protected $jwtService;

    public function __construct(JwtService $jwtService)
    {
        $this->jwtService = $jwtService;
    }

    public function createApplication(JobApplicationRequest $request)
    {
        $verificationToken = Str::random(40);

        $jobApplicant = JobApplicant::create([
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'verification_token' => $verificationToken,
        ]);

        // Send email verification link
        Mail::to($jobApplicant->email)->send(new \App\Mail\VerifyEmail($jobApplicant));

        return response()->json([
            'message' => 'Verification email sent successfully!',
            'applicant' => new JobApplicationResource($jobApplicant)
        ], 201);
    }

    public function verifyEmail($token)
    {
        $applicant = JobApplicant::where('verification_token', $token)->first();

        if (!$applicant) {
            return response()->json(['message' => 'Invalid or expired token'], 400);
        }

        // Mark applicant as verified
        $applicant->update([
            'email_verified_at' => now(),
            'verification_token' => null,
        ]);

        // Create a User record
        $user = \App\Models\User::updateOrCreate(
            ['email' => $applicant->email],
            [
                'name' => "{$applicant->first_name} {$applicant->last_name}",
                'password' => bcrypt(Str::random(16)), // Temporary password
            ]
        );

        // Generate JWT token for portal access
        $tokens = $this->jwtService->generateTokens($user);

        // Send portal access email
        Mail::to($applicant->email)->send(new \App\Mail\PortalAccessEmail($tokens['access_token']));

        return response()->json([
            'message' => 'Email verified successfully! Portal access has been sent.',
            'token' => $tokens['access_token']
        ], 200);
    }
}
