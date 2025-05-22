<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;


class JobApplication extends Authenticatable implements JWTSubject
{
    use HasFactory, UsesTenantConnection;



    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    protected $table = 'job_applications';

    protected $fillable = [
        'portal_token'
    ];

    public function jobApplicant()
    {
        return $this->belongsTo(JobApplicant::class, 'job_applicant_id');
    }

    public function jobApplicantInformation()
    {
        return $this->hasOneThrough(
            JobApplicantInformation::class,
            JobApplicant::class,
            'id',                    // Foreign key on JobApplicant (local FK to JobApplication)
            'job_applicant_id',     // Foreign key on JobApplicantInformation (local FK to JobApplicant)
            'job_applicant_id',     // Local key on JobApplication
            'id'                    // Local key on JobApplicant
        );
    }

    public function jobSelectionOptions()
    {
        return $this->hasMany(JobSelectionOption::class, 'job_application_id');
    }

    public function jobApplicationProgress()
    {
        return $this->hasMany(JobApplicationProgress::class, 'job_application_id');
    }

    public function jobInterviewScheduling()
    {
        return $this->hasMany(JobInterviewScheduling::class, 'job_application_id');
    }

    // public function getActivitylogOptions(): LogOptions
    // {
    //     return LogOptions::defaults()
    //         ->logOnly($this->getFillable()) // Log all fillable, but only if changed
    //         ->logOnlyDirty()
    //         ->useLogName('job application')
    //         ->setDescriptionForEvent(function (string $eventName) {
    //             $dirty = collect($this->getDirty())->except('updated_at')->toJson();

    //             return ucfirst($eventName) . " job application: {$dirty}";
    //         });
    // }
}
