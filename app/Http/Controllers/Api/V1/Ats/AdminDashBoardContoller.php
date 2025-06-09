<?php

namespace App\Http\Controllers\Api\V1\Ats;

use App\Models\JobApplicant;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class AdminDashBoardContoller extends Controller
{
    public function getDashboardMetrics(): JsonResponse
    {
        // Total applications
        $totalCandidates = JobApplicant::count();

        // Shortlisted candidates (you may need to adjust logic depending on your schema)
        $shortlisted = JobApplicant::whereHas('jobApplicationProgress', function ($query) {
            $query->where('status', 'shortlisted');
        })->count();

        // Rejected candidates
        $rejected = JobApplicant::whereHas('jobApplicationProgress', function ($query) {
            $query->where('status', 'rejected');
        })->count();

        // Hired candidates
        $hired = JobApplicant::whereHas('jobApplicationProgress', function ($query) {
            $query->where('status', 'hired');
        })->count();

        return response()->json([
            'totalCandidates' => $totalCandidates,
            'shortlisted' => $shortlisted,
            'rejected' => $rejected,
            'hired' => $hired,
        ]);
    }

    public function getMonthlyApplicationCounts(): JsonResponse
    {
        $applicationsByMonth = JobApplicant::select(
            DB::raw("MONTH(created_at) as month"),
            DB::raw("COUNT(*) as value")
        )
            ->groupBy(DB::raw("MONTH(created_at)"))
            ->orderBy(DB::raw("MONTH(created_at)"))
            ->get()
            ->mapWithKeys(function ($item) {
                return [intval($item->month) => $item->value];
            });

        // Ensure all months are present (fill 0 if missing)
        $months = collect(range(1, 12))->map(function ($monthNumber) use ($applicationsByMonth) {
            return [
                'month' => Carbon::create()->month($monthNumber)->format('M'),
                'value' => $applicationsByMonth->get($monthNumber, 0),
            ];
        });

        return response()->json($months);
    }
}
