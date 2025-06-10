<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;


class TrainingCourseEnrollment extends Model
{
    use HasFactory, UsesTenantConnection, LogsActivity;

    protected $table = 'training_course_enrollments';

    protected $fillable = [
        'course_id',
        'employee_id',
        'enrollment_date',
        'status'
    ];

    public function course()
    {
        return $this->belongsTo(TrainingCourse::class, 'course_id');
    }
    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly($this->getFillable()) // Log all fillable, but only if changed
            ->logOnlyDirty()
            ->useLogName('course enrollment')
            ->setDescriptionForEvent(function (string $eventName) {
                $dirty = collect($this->getDirty())->except('updated_at')->toJson();
    
                return ucfirst($eventName) . " course enrollment: {$dirty}";
            });
    }
}
