<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// use Spatie\Activitylog\LogOptions;
// use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class JobApplicationProgress extends Model
{
    use UsesTenantConnection;

    protected $table = 'job_application_progress';

    protected $fillable = [
        'job_application_id',
        'job_application_phase_id',
        'reviewed_by',
        'reviewer_remarks',
        'status',
        'start_date',
        'end_date'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function jobApplication()
    {
        return $this->belongsTo(JobApplication::class, 'job_application_id');
    }

    public function jobApplicationPhases()
    {
        return $this->belongsTo(JobApplicationPhase::class, 'job_application_phase_id');
    }


    // public function getActivitylogOptions(): LogOptions
    // {
    //     return LogOptions::defaults()
    //         ->logOnly(['job_application_id', 'job_application_phase_id', 'status', 'reviewer_remarks'])
    //         ->logOnlyDirty()
    //         ->useLogName('job_application_progress')
    //         ->setDescriptionForEvent(fn(string $eventName) => ucfirst($eventName) . " job application progress with status: {$this->status}");
    // }
}
