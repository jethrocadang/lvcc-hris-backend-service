<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly($this->getFillable()) // Log all fillable, but only if changed
            ->logOnlyDirty()
            ->useLogName('job interview schedule')
            ->setDescriptionForEvent(function (string $eventName) {
                $dirty = collect($this->getDirty())->except('updated_at')->toJson();
    
                return ucfirst($eventName) . " job interview schedule: {$dirty}";
            });
    }
}
