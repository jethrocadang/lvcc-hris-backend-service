<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

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
        'avatar_url'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

}
