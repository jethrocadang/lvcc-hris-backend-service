<?php

namespace App\Http\Controllers;
use App\Models\ActivityLog;

use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function getActivityLogs(){
        $activityLogs = ActivityLog::all();
        return response()->json(['activity_logs' => $activityLogs], 200);
    }
}
