<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Department extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'name',
        'description'
    ];

    public function jobPositions()
    {
        return $this->belongsToMany(JobPosition::class, 'department_positions');
    }

    /**
     * Define Spatie's logging options.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'description']) // Log only these attributes
            ->logOnlyDirty() // Log only changed attributes
            ->useLogName('department') // Set custom log name
            ->setDescriptionForEvent(fn(string $eventName) => ucfirst($eventName) . " department: {$this->name}");
    }
}
