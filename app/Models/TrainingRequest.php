<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class TrainingRequest extends Model
{
    use HasFactory, UsesTenantConnection, LogsActivity;

    protected $table = 'employee_training_requests';

    protected $fillable = [
        'employee_id',
        'supervisor_id',
        'officer_id',
        'subject',
        'description',
        'justification',
        'expected_outcome',
        'training_format',
        'estimated_duration',
        'supervisor_status',
        'supervisor_reviewed_at',
        'officer_status',
        'officer_reviewed_at',
        'request_status',
        'rejection_reason'
    ];

    public function  employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function  supervisor()
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    public function  officer()
    {
        return $this->belongsTo(User::class, 'officer_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly($this->getFillable()) // Log all fillable, but only if changed
            ->logOnlyDirty()
            ->useLogName('training request')
            ->setDescriptionForEvent(function (string $eventName) {
                $dirty = collect($this->getDirty())->except('updated_at')->toJson();

                return ucfirst($eventName) . " training request: {$dirty}";
            });
    }
}
