<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Spatie\Multitenancy\Models\Concerns\UsesLandlordConnection;

class Department extends Model
{
    use HasFactory, LogsActivity, UsesLandlordConnection;

    protected $connection = 'landlord';

    protected $fillable = [
        'name',
        'description'
    ];

    public function jobPositions()
    {
        return $this->belongsToMany(JobPosition::class, 'department_positions');
    }

    /**
     * Get the job posts associated with this department.
     * This relationship crosses database connections (landlord to tenant).
     */
    public function jobPosts()
    {
        // Note: This relationship will only work when a tenant is active
        return $this->hasMany(JobPost::class);
    }

    /**
     * Define Spatie's logging options.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly($this->fillable) // Log all fillable, but only if changed
            ->logOnlyDirty()
            ->useLogName('department')
            ->setDescriptionForEvent(function (string $eventName) {
                $dirty = collect($this->getDirty())->except('updated_at')->toJson();

                return ucfirst($eventName) . " department: {$dirty}";
            });
    }
}
