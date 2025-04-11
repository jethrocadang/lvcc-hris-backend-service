<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;
use Tymon\JWTAuth\Contracts\JWTSubject;

class JobApplication extends Model implements JWTSubject
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
}
