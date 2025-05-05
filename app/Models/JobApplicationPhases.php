<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use App\Models\JobApplicationProgress;

class JobApplicationPhases extends Model
{
    use UsesTenantConnection, LogsActivity;

    protected $table = 'job_application_phases';
    protected $fillable = [
        'email_template_id',
        'name',
        'description',
        'sequence_order',
    ];

    public function jobApplicationProgress()
    {
        return $this->hasMany(JobApplicationProgress::class, 'job_application_phase_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly($this->getFillable()) // Log all fillable, but only if changed
            ->logOnlyDirty()
            ->useLogName('job applicant phase')
            ->setDescriptionForEvent(function (string $eventName) {
                $dirty = collect($this->getDirty())->except('updated_at')->toJson();
    
                return ucfirst($eventName) . " job applicant phase: {$dirty}";
            });
    }
}
