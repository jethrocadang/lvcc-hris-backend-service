<?php

namespace App\Http\Controllers\Api\V1\Ats;

use App\Http\Controllers\Controller;
use App\Services\Ats\JobApplicationAdminService;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Http\Request;

class JobApplicationAdminController extends Controller
{
    use ApiResponse;


    private JobApplicationAdminService $jobApplicationAdmin;

    public function __construct(JobApplicationAdminService $jobApplicationAdmin)
    {
        $this->jobApplicationAdmin = $jobApplicationAdmin;
    }

    public function getAllJobApplications()
    {
        try {
            $jobApplications = $this->jobApplicationAdmin->getAllJobApplication(request());

            return $this->successResponse("Success", $jobApplications, 200);
        } catch (Exception $e) {
            return $this->errorResponse("Error", [$e->getMessage()], 500);
        }
    }

    public function getJobApplication(int $id)
    {
        try {
            $jobApplication = $this->jobApplicationAdmin->getJobApplication($id);
            return $this->successResponse("Success", $jobApplication, 200);
        } catch (Exception $e) {
            return $this->errorResponse("Error", [$e->getMessage()], 500);
        }
    }


    public function getAllJobApplicationsBySlug(string $slug)
    {
        try {
            $jobApplications = $this->jobApplicationAdmin->getAllJobApplicationBySlug($slug, request());

            return $this->successResponse("Success", $jobApplications, 200);
        } catch (Exception $e) {
            return $this->errorResponse("Error", [$e->getMessage()], 500);
        }
    }
}
