<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\ActivityLog;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function getActivityLogs(){
        $activityLogs = ActivityLog::all();
        return response()->json(['activity_logs' => $activityLogs], 200);
    }
}
