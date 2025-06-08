<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class JobApplicationPhase extends Model
{
    use UsesTenantConnection;

    protected $table = 'job_application_phases';
    protected $fillable = [
        'acceptance_email_template_id',
        'rejection_email_template_id',
        'title',
        'description',
        'sequence_order',
        'slug'
    ];

    public function jobApplicationProgress()
    {
        return $this->hasMany(JobApplicationProgress::class, 'job_application_phase_id');
    }

    public function acceptanceTemplate()
    {
        return $this->belongsTo(AtsEmailTemplate::class, 'acceptance_email_template_id');
    }

    public function rejectionTemplate()
    {
        return $this->belongsTo(AtsEmailTemplate::class, 'rejection_email_template_id');
    }

    /**
     * Define Spatie's logging options.
    //  */
    // public function getActivitylogOptions(): LogOptions
    // {
    //     return LogOptions::defaults()
    //         ->logOnly(['title', 'description']) // Log only these attributes
    //         ->logOnlyDirty() // Log only changed attributes
    //         ->useLogName('email') // Set custom log name
    //         ->setDescriptionForEvent(fn(string $eventName) => ucfirst($eventName) . " email: {$this->title}");
    // }
}
