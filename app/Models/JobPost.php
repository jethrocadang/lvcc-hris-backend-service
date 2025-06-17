<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class JobPost extends Model
{
    use HasFactory, UsesTenantConnection, LogsActivity;

    protected $fillable = [
        'department_id',
        'work_type',
        'job_type',
        'title',
        'description',
        'icon_url',
        'status',
        'location',
        'category'
    ];

    /**
     * Get the department associated with the job post.
     * This relationship crosses database connections (tenant to landlord).
     */
    public function department()
    {
        try {
            return $this->belongsTo(Department::class, 'department_id')
                ->on('landlord');
        } catch (\Exception $e) {
            \Log::error('Error in department relationship: ' . $e->getMessage());
            return $this->morphTo()->morphWithoutConstraints();
        }
    }

    public function jobSelectionOption()
    {
        return $this->hasMany(JobSelectionOption::class, 'job_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly($this->getFillable()) // Log all fillable, but only if changed
            ->logOnlyDirty()
            ->useLogName('job post')
            ->setDescriptionForEvent(function (string $eventName) {
                $dirty = collect($this->getDirty())->except('updated_at')->toJson();

                return ucfirst($eventName) . " job post: {$dirty}";
            });
    }
}
