<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class AtsEmailTemplate extends Model
{
    use UsesTenantConnection, HasFactory, LogsActivity;

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

        public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly($this->getFillable()) // Log all fillable, but only if changed
            ->logOnlyDirty()
            ->useLogName('ats email')
            ->setDescriptionForEvent(function (string $eventName) {
                $dirty = collect($this->getDirty())->except('updated_at')->toJson();
    
                return ucfirst($eventName) . " ats email: {$dirty}";
            });
    }
}
