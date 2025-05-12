<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;


class TrainingCourse extends Model
{
    use HasFactory, UsesTenantConnection, LogsActivity;

    protected $table = 'employee_training_courses';

    protected $connection = 'tenant';

    protected $fillable =[
        'author_id',
        'title',
        'description',
        'type',
        'thumbnail_url',
        'max_participants',
        'current_participants',
        'enrollment_deadline'
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly($this->getFillable()) // Log all fillable, but only if changed
            ->logOnlyDirty()
            ->useLogName('training course')
            ->setDescriptionForEvent(function (string $eventName) {
                $dirty = collect($this->getDirty())->except('updated_at')->toJson();
    
                return ucfirst($eventName) . " training course: {$dirty}";
            });
    }
}
