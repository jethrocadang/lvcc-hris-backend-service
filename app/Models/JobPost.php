<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class JobPost extends Model
{
    use HasFactory, UsesTenantConnection;

    protected $fillable = [
        'work_type',
        'job_type',
        'title',
        'description',
        'icon_url',
        'status',
        'location',
        'schedule' // updated schedule to category [teaching, non-teaching]
    ];


    public function jobSelectionOption()
    {
        $this->hasMany(JobSelectionOption::class, 'job_id');
    }
}
