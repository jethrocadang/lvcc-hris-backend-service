<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class JobApplicationProgress extends Model
{
    use UsesTenantConnection;

    protected $table = 'job_application_progress';

    protected $fillable = [
        'job_application_id',
        'job_application_phase_id',
        'reviewed_by',
        'status',
        'start_date',
        'end_date'
    ];

    public function jobApplication()
    {
        return $this->belongsTo(JobApplication::class, 'job_application_id');
    }

    public function jobApplicationPhases()
    {
        return $this->belongsTo(JobApplicationPhases::class, 'job_application_phase_id');
    }
}
