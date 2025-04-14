<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;


class JobApplication extends Authenticatable implements JWTSubject
{
    use HasFactory, UsesTenantConnection;

    // public function __construct(array $attributes = [])
    // {
    //     parent::__construct($attributes);

    //     // Ensure tenant connection is always set
    //     $this->setConnection(config('multitenancy.tenant_database_connection_name'));
    // }

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
}
