<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class JobInterviewScheduling extends Model
{
    use UsesTenantConnection;

    protected $table = 'applicant_interview_schedules';

    protected $fillable = [
        'interview_schedule_slot_id',
        'job_application_id',
        'application_progress_id',
        'selected_date',
        'selected_time',
        'schedule_status'
    ];

    /**
     * Belongs to the job application.
     */
    public function jobApplication()
    {
        return $this->belongsTo(JobApplication::class, 'job_appplication_id');
    }

    /**
     * Belongs to the application progress tracker.
     */
    public function jobApplicationProgress()
    {
        return $this->belongsTo(JobApplicationProgress::class, 'application_progress_id');
    }

    /**
     * Belongs to an interview schedule slot from landlord DB.
     */
    public function scheduleSlot()
    {
        return $this->belongsTo(InterviewScheduleSlot::class, 'interview_schedule_slot_id');
    }
}
