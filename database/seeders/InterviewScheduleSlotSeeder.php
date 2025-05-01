<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\InterviewScheduleSlot;
use Carbon\Carbon;

class InterviewScheduleSlotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminId = 1; // Replace this with a valid user ID from your landlord DB

        $baseDate = Carbon::today()->addDays(1); // Start from tomorrow

        foreach (range(0, 4) as $dayOffset) {
            $date = $baseDate->copy()->addDays($dayOffset);

            // Example time slots: 8 AM, 10 AM, 1 PM
            $times = ['08:00:00', '10:00:00', '13:00:00'];

            foreach ($times as $time) {
                InterviewScheduleSlot::create([
                    'admin' => $adminId,
                    'scheduled_date' => $date->toDateString(),
                    'start_time' => $time,
                    'slot_status' => 'available', // or 'booked', 'cancelled' etc.
                ]);
            }
        }
    }
}
