<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class JobApplicationProgress extends Model
{
    use UsesTenantConnection, LogsActivity;

    protected $table = 'job_application_progress';

    protected $fillable = [
        'job_application_id',
        'job_application_phase_id',
        'reviewed_by',
        'reviewer_remarks',
        'screening_type',
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

    public function phase()
    {
        return $this->belongsTo(JobApplicationPhase::class, 'job_application_phase_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly($this->getFillable()) // Log all fillable, but only if changed
            ->logOnlyDirty()
            ->useLogName('job application progress')
            ->setDescriptionForEvent(function (string $eventName) {
                $dirty = collect($this->getDirty())->except('updated_at')->toJson();

                return ucfirst($eventName) . " job application progress: {$dirty}";
            });
    }
}
