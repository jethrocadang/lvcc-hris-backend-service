<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class JobSelectionOption extends Model
{
    use UsesTenantConnection;

    protected $table = 'job_selection_options';
    protected $fillable = [
        'job_id',
        'job_application_id',
        'priority',
        'status'
    ];

    public function jobApplication()
    {
        $this->belongsTo(JobApplication::class, 'job_application_id');
    }

    public function jobPost()
    {
        $this->belongsTo(JobPost::class, 'job_id');
    }

    // public function getActivitylogOptions(): LogOptions
    // {
    //     return LogOptions::defaults()
    //         ->logOnly($this->getFillable()) // Log all fillable, but only if changed
    //         ->logOnlyDirty()
    //         ->useLogName('job selection')
    //         ->setDescriptionForEvent(function (string $eventName) {
    //             $dirty = collect($this->getDirty())->except('updated_at')->toJson();

    //             return ucfirst($eventName) . " job selection: {$dirty}";
    //         });
    // }
}
