<?php

namespace App\Http\Controllers\Api\V1\Ats;

use App\Models\JobApplicant;
use App\Models\JobApplicationPhase;
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

        // Get the phase ID for "Shortlisted"
        $shortlistedPhaseId = JobApplicationPhase::where('slug', 'phase-two')->value('id');

        // Applicants currently in the Shortlisted phase (latest progress is Shortlisted)
        $shortlistedApplicants = JobApplicant::whereHas('jobApplicationProgress', function ($query) use ($shortlistedPhaseId) {
            $query->where('job_application_phase_id', $shortlistedPhaseId);
        })->get();

        // Count of shortlisted applicants
        $shortlisted = $shortlistedApplicants->count();

        // Rejected candidates (status = rejected in any phase)
        $rejected = JobApplicant::whereHas('jobApplicationProgress', function ($query) {
            $query->where('status', 'rejected');
        })->count();

        // Hired candidates (status = hired in any phase)
        $hired = JobApplicant::whereHas('jobApplicationProgress', function ($query) {
            $query->where('status', 'hired');
        })->count();

        return response()->json([
            'totalCandidates' => $totalCandidates,
            'shortlisted' => $shortlisted,
            'shortlisted_applicants' => $shortlistedApplicants, // You can remove this if you only want the count
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

    public function applicationFunnel(): JsonResponse
    {
        // Define the slugs for each phase in order
        $phaseSlugs = [
            'phase-one',
            'phase-two',
            'phase-three',
            'phase-four',
            'phase-five-demo',
            'phase-five-technical',
            'phase-six',
            'phase-seven',
        ];

        $funnel = [];

        foreach ($phaseSlugs as $slug) {
            // Get the phase ID for the current slug
            $phase = JobApplicationPhase::where('slug', $slug)->first();

            if (!$phase) {
                $funnel[] = [
                    'slug' => $slug,
                    'title' => null,
                    'count' => 0,
                ];
                continue;
            }

            // Count applicants whose latest progress is this phase
            $count = JobApplicant::whereHas('jobApplicationProgress', function ($query) use ($phase) {
                $query->where('job_application_phase_id', $phase->id);
            })->count();

            $funnel[] = [
                'slug' => $slug,
                'title' => $phase->title,
                'count' => $count,
            ];
        }

        return response()->json($funnel);
    }
}
