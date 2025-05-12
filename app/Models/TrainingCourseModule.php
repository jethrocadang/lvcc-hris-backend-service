<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class TrainingCourseModule extends Model
{
    use HasFactory, UsesTenantConnection, LogsActivity;

    protected $table = 'training_course_modules';

    protected $fillable = [
        'course_id',
        'title',
        'description',
        'type',
        'certificate_url',
        'video_url',
        'thumbnail_url',
        'sequence_order',
        'file_content',
        'text_content',
        'image_content',
        'expiration_date',
    ];

    public function course()
    {
        return $this->belongsTo(TrainingCourse::class, 'course_id');
    }

    public function  user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly($this->getFillable()) // Log all fillable, but only if changed
            ->logOnlyDirty()
            ->useLogName('module')
            ->setDescriptionForEvent(function (string $eventName) {
                $dirty = collect($this->getDirty())->except('updated_at')->toJson();
    
                return ucfirst($eventName) . " module: {$dirty}";
            });
    }
}
