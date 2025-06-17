<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class JobPostCollection extends ResourceCollection
{
    /**
     * The resource that this resource collects.
     *
     * @var string
     */
    public $collects = JobPostResource::class;

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->collection->map(function ($jobPost) use ($request) {
            // Ensure each job post has its department loaded if possible
            if (!$jobPost->relationLoaded('department') && $jobPost->department_id) {
                try {
                    // Try to load the department
                    $department = \App\Models\Department::find($jobPost->department_id);
                    if ($department) {
                        $jobPost->setRelation('department', $department);
                    }
                } catch (\Exception $e) {
                    // Silently fail
                }
            }

            return new JobPostResource($jobPost);
        })->toArray();
    }
}
