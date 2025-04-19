<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class JobApplicationPhases extends Model
{
    use UsesTenantConnection;

    protected $table = 'job_application_phases';
    protected $fillable = [
        'email_template_id',
        'name',
        'description',
        'sequence_order',
    ];
}
