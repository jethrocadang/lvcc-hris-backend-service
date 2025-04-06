<?php

namespace App\Http\Controllers\Api\V1\Ats;

use App\Models\JobApplicant;
use App\Http\Controllers\Controller;
use App\Http\Requests\Ats\JobApplicationRequest;
use App\Http\Resources\JobApplicationResource;
use App\Mail\PortalAccessEmail;
// use App\Services\Auth\JwtService;
use App\Mail\VerificationEmail;
use App\Mail\PortalAccessEmailEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Spatie\Multitenancy\Models\Tenant;
use Illuminate\Support\Facades\DB;



class JobApplicationController extends Controller
{
    protected $jwtService;

    // public function __construct(JwtService $jwtService)
    // {
    //     $this->jwtService = $jwtService;
    // }

    public function test()
    {

        \Log::info('Current Tenant:', ['tenant' => Tenant::current()]);
        return response()->json(['tenant' => Tenant::current()]);
    }

    //Creating a job application
    public function createApplication(JobApplicationRequest $request)
    {
        \Log::info('createApplication() method reached with request data:', $request->all());

        $tenant = Tenant::current();

        if (!$tenant) {
            return response()->json(['message' => 'No active tenant'], 400);
        }

        \Log::info('Active Tenant:', ['database' => $tenant->database]);

        // Confirm which database is being used
        \Log::info('Current Database:', ['db' => DB::connection()->getDatabaseName()]);

        // Confirm that JobApplicant is using the right connection
        $jobApplicantModel = new JobApplicant();
        \Log::info('JobApplicant Connection:', ['connection' => $jobApplicantModel->getConnectionName()]);

        $verificationToken = Str::random(40);

        $jobApplicant = new JobApplicant([
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'verification_token' => $verificationToken,
        ]);

        // Ensure the model is being saved to the tenant database
        $jobApplicant->save();

        Mail::to($jobApplicant->email)->send(new VerificationEmail($jobApplicant));

        return response()->json([
            'message' => 'Verification email sent successfully!',
            'applicant' => new JobApplicationResource($jobApplicant)
        ], 201);
    }


    public function verifyEmail($token)
    {

        $tenant = Tenant::current();

        if (!$tenant) {
            return response()->json(['message' => 'No active tenant'], 400);
        }
        
        $jobapplicant = JobApplicant::where('verification_token', $token)->first();

        if (!$jobapplicant) {
            return response()->json(['message' => 'Invalid or expired token'], 400);
        }

        // Mark applicant as verified
        $jobapplicant->update([
            'email_verified_at' => now(),
            'verification_token' => null,
        ]);

        // Generate JWT token for portal access
        $portalToken = Str::random(40);

        // Send portal access email
        Mail::to($jobapplicant->email)->send(new PortalAccessEmail($portalToken['access_token']));

        return response()->json([
            'message' => 'Email verified successfully! Portal access has been sent.',
            'token' => $portalToken['access_token']
        ], 200);
    }
}
