<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class EvaluationForm extends Model
{
    use HasFactory, UsesTenantConnection, LogsActivity;

    protected $connection = 'tenant';

    protected $fillable = [
        'employee_training_course_id',
        'title',
        'is_active'
    ];

    public function trainingCourse()
    {
        return $this->belongsTo(TrainingCourse::class, 'employee_training_course_id');
    }

    public function categories()
    {
        return $this->hasMany(EvaluationCategory::class);
    }

    public function responses()
    {
        return $this->hasMany(EvaluationResponse::class);
    }

    public function comments()
    {
        return $this->hasMany(EvaluationComment::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly($this->getFillable())
            ->logOnlyDirty()
            ->useLogName('evaluation form')
            ->setDescriptionForEvent(function (string $eventName) {
                $dirty = collect($this->getDirty())->except('updated_at')->toJson();

                return ucfirst($eventName) . " evaluation form: {$dirty}";
            });
    }
}
