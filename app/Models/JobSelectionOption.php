<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class JobSelectionOption extends Model
{
    use UsesTenantConnection;

    protected $table = 'job_selection_options';
    protected $fillable = [
        'job_id',
        'job_application_id',
        'priority',
        'status'
    ];

    public function jobApplication()
    {
        $this->belongsTo(JobApplication::class, 'job_application_id');
    }

    public function jobPost()
    {
        $this->belongsTo(JobPost::class, 'job_id');
    }
}
