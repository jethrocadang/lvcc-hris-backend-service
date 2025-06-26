<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ExternalTrainingAttendance extends Model
{
    use HasFactory, UsesTenantConnection, LogsActivity;

    protected $table = 'external_training_attendance';

    protected $fillable = [
        'employee_id',
        'training_type',
        'title',
        'description',
        'provider',
        'training_mode',
        'location',
        'certificate_url',
        'hours_completed',
        'date_started',
        'date_completed',
    ];

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly($this->getFillable()) // Log all fillable, but only if changed
            ->logOnlyDirty()
            ->useLogName('external training attendance')
            ->setDescriptionForEvent(function (string $eventName) {
                $dirty = collect($this->getDirty())->except('updated_at')->toJson();

                return ucfirst($eventName) . " external training attendance'): {$dirty}";
            });
    }
}
