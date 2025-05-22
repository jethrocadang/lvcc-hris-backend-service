<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class JobPost extends Model
{
    use HasFactory, UsesTenantConnection;

    protected $fillable = [
        'work_type',
        'job_type',
        'title',
        'description',
        'icon_url',
        'status',
        'location',
        'category' 
    ];


    public function jobSelectionOption()
    {
        $this->hasMany(JobSelectionOption::class, 'job_id');
    }

    // public function getActivitylogOptions(): LogOptions
    // {
    //     return LogOptions::defaults()
    //         ->logOnly($this->getFillable()) // Log all fillable, but only if changed
    //         ->logOnlyDirty()
    //         ->useLogName('job post')
    //         ->setDescriptionForEvent(function (string $eventName) {
    //             $dirty = collect($this->getDirty())->except('updated_at')->toJson();

    //             return ucfirst($eventName) . " job post: {$dirty}";
    //         });
    // }
}
