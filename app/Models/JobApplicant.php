<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class JobApplicant extends Model
{
    use HasFactory, UsesTenantConnection;
    protected $table = 'job_applicants';

    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'email_verified_at',
        'status',
        'avatar_url',
        'verification_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function jobApplication()
    {
        return $this->hasOne(JobApplication::class, 'job_applicant_id');
    }

    // In JobApplicant.php
    public function jobApplicationProgress()
    {
        return $this->hasManyThrough(
            JobApplicationProgress::class,
            JobApplication::class,
            'job_applicant_id', // Foreign key on job_applications
            'job_application_id', // Foreign key on job_application_progress
            'id', // Local key on job_applicants
            'id' // Local key on job_applications
        );
    }


    public function jobApplicantInformation()
    {
        return $this->hasOne(JobApplicantInformation::class, 'job_applicant_id');
    }

    // public function getActivitylogOptions(): LogOptions
    // {
    //     return LogOptions::defaults()
    //         ->logOnly($this->getFillable()) // Log all fillable, but only if changed
    //         ->logOnlyDirty()
    //         ->useLogName('job applicant')
    //         ->setDescriptionForEvent(function (string $eventName) {
    //             $dirty = collect($this->getDirty())->except('updated_at')->toJson();

    //             return ucfirst($eventName) . " job applicant: {$dirty}";
    //         });
    // }

}
