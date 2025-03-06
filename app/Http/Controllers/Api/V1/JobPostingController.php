<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Job\JobPostRequest;
use App\Http\Resources\JobPostResource;
use App\Models\JobPost;
use Illuminate\Support\Facades\Auth;

class JobPostingController extends Controller
{
    public function createJobPost(JobPostRequest $request)
    {
        //create job post
        $jobPost = JobPost::create($request->validated());
        //return response with job post data
        return response()->json([
            'message' => 'Job posting created successfully!',
            'job' => new JobPostResource($jobPost)
        ], 201);
    }

    public function getJobPost()
    {
        //get all job posts
        $jobPost = JobPost::all();
        return response()->json(['job_posts' => JobPostResource::collection($jobPost)], 200);
    }

    public function updateJobPost(JobPostRequest $request, $id)
    {
        // Find job post by id
        $jobPost = JobPost::findOrFail($id);
        // Update the chosen job post
        $jobPost->update($request->validated());
        return response()->json([
            'message' => 'Job posting updated successfully!',
            'job' => new JobPostResource($jobPost)
        ], 200);
    }

    public function deleteJobPost($id)
    {
        //delete specific job post
        $jobPost = JobPost::findOrFail($id);
        $jobPost->delete();
        return response()->json(['message' => 'Job posting deleted successfully!'], 200);
    }
}
