<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class EmployeeCourseProgress extends Model
{
    use HasFactory, UsesTenantConnection, LogsActivity;

    protected $table = 'employee_course_progress';

    protected $fillable = [
        'employee_id',
        'course_id',
        'module_id',
        'status',
        'watched_seconds',
        'last_position',
        'completion_date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function course()
    {
        return $this->belongsTo(TrainingCourse::class, 'course_id');
    }

    public function module()
    {
        return $this->belongsTo(TrainingCourseModule::class, 'module_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly($this->getFillable()) // Log all fillable, but only if changed
            ->logOnlyDirty()
            ->useLogName('course progress')
            ->setDescriptionForEvent(function (string $eventName) {
                $dirty = collect($this->getDirty())->except('updated_at')->toJson();

                return ucfirst($eventName) . " course progress: {$dirty}";
            });
    }
}
