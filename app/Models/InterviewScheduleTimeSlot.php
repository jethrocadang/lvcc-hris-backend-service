<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InterviewScheduleTimeSlot extends Model
{
        use HasFactory;

    protected $table = 'interview_schedule_time_slots';

    protected $fillable = [
        'interview_slot_id',
        'start_time',
        'available',
    ];

    // Relationships
    public function scheduleSlot()
    {
        return $this->belongsTo(InterviewScheduleSlot::class, 'interview_slot_id');
    }
}
