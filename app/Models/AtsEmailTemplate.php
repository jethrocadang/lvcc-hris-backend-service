<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class AtsEmailTemplate extends Model
{
    use UsesTenantConnection, HasFactory;

    protected $fillable = [
        "user_id",
        'type',
        'subject',
        'body'
    ];


    public function phaseUsingAsAcceptance()
    {
        return $this->hasOne(JobApplicationPhase::class, 'acceptance_email_template_id');
    }

    public function phaseUsingAsRejection()
    {
        return $this->hasOne(JobApplicationPhase::class, 'rejection_email_template_id');
    }
}
