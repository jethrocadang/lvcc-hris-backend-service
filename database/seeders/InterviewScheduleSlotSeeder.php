<?php

namespace Database\Seeders;

use App\Models\InterviewScheduleSlot;
use App\Models\InterviewScheduleTimeSlot;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class InterviewScheduleSlotSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::first(); // Make sure there's at least one admin user

        if (!$admin) {
            throw new \Exception("No admin user found. Please create at least one user.");
        }

        $startDate = Carbon::now()->addDay(); // Start from tomorrow
        $numberOfDays = 7;
        $daysAdded = 0;

        while ($daysAdded < $numberOfDays) {
            if ($startDate->isWeekday()) {
                DB::transaction(function () use ($admin, $startDate) {
                    $slot = InterviewScheduleSlot::create([
                        'admin' => $admin->id,
                        'scheduled_date' => $startDate->format('Y-m-d'),
                    ]);
                    $timeSlots = collect(range(8, 16))
                        ->reject(fn($hour) => $hour === 12) // Exclude lunch hour
                        ->map(fn($hour) => [
                            'start_time' => sprintf('%02d:00:00', $hour),
                            'available' => true,
                        ])
                        ->toArray();

                    $slot->timeSlots()->createMany($timeSlots);
                });

                $daysAdded++;
            }

            $startDate->addDay();
        }
    }
}
