<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JobPost;
use Illuminate\Support\Facades\Auth;

class JobPostingController extends Controller
{
    public function createJobPost(Request $request)
    {
        //create new job post
        $request->validate([
            'work_type' => 'required|in:full-time,part-time,internship',
            'job_type' => 'required|in:onsite,remote,hybrid',
            'title' => 'required|string|max:40',
            'description' => 'required|string',
            'icon_url' => 'nullable|string',
            'status' => 'required|in:open,closed',
            'location' => 'nullable|string',
            'schedule' => 'nullable|string',
        ]);

        $jobPost = JobPost::create([
            'work_type' => $request->work_type,
            'job_type' => $request->job_type,
            'title' => $request->title,
            'description' => $request->description,
            'icon_url' => $request->icon_url,
            'status' => $request->status,
            'location' => $request->location,
            'schedule' => $request->schedule,
        ]);

         return response()->json(['message' => 'Job posting created successfully!',
                                  'job' => $jobPost], 200);
    }

    public function getJobPost()
    {
        //get all job posts for showing in the frontend
        $jobPost = JobPost::all();
        return response()->json(['job_posts' => $jobPost], 200);
    }

    public function updateJobPost(Request $request, $id)
    {
        //update job post
        $request->validate([
            'work_type' => 'required|in:full-time,part-time,internship',
            'job_type' => 'required|in:onsite,remote,hybrid',
            'title' => 'required|string|max:40',
            'description' => 'required|string',
            'icon_url' => 'nullable|string',
            'status' => 'required|in:open,closed',
            'location' => 'nullable|string',
            'schedule' => 'nullable|string',
        ]);

        $jobPost = JobPost::find($id);
        $jobPost->update([
            'work_type' => $request->work_type,
            'job_type' => $request->job_type,
            'title' => $request->title,
            'description' => $request->description,
            'icon_url' => $request->icon_url,
            'status' => $request->status,
            'location' => $request->location,
            'schedule' => $request->schedule,
        ]);

        return response()->json(['message' => 'Job posting updated successfully!',
                                  'job' => $jobPost], 200);
    }

    public function deleteJobPost($id)
    {
        //delete specific job post
        $jobPost = JobPost::find($id);
        $jobPost->delete();
        return response()->json(['message' => 'Job posting deleted successfully!'], 200);
    }
}
