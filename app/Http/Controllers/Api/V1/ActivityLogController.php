<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\ActivityLogResource;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use App\Http\Controllers\Controller;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = Activity::with('causer');

        // Optional filters
        if ($request->has('causer_id')) {
            $query->where('causer_id', $request->causer_id);
        }

        if ($request->has('event')) {
            $query->where('event', $request->event);
        }

        if ($request->has('subject_type')) {
            $query->where('subject_type', $request->subject_type);
        }

        $logs = $query->latest()->paginate($request->get('per_page', 10));

        return ActivityLogResource::collection($logs);
    }
}

