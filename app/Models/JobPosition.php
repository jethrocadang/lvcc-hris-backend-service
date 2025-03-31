<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class JobPosition extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'title',
        'description'
    ];

        /**
     * Define Spatie's logging options.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['title', 'description']) // Log only these attributes
            ->logOnlyDirty() // Log only changed attributes
            ->useLogName('job position') // Set custom log name
            ->setDescriptionForEvent(fn(string $eventName) => ucfirst($eventName) . " job position: {$this->title}");
    }
}
