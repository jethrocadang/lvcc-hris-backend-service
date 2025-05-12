<?php

namespace App\Models;

use App\Models\Department;
use App\Models\JobPosition;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class DepartmentJobPosition extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'department_positions';
    

    protected $fillable = ['department_id', 'job_position_id'];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function position()
    {
        return $this->belongsTo(JobPosition::class, 'job_position_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly($this->getFillable()) // Log all fillable, but only if changed
            ->logOnlyDirty()
            ->useLogName('department job position')
            ->setDescriptionForEvent(function (string $eventName) {
                $dirty = collect($this->getDirty())->except('updated_at')->toJson();
    
                return ucfirst($eventName) . " department job position: {$dirty}";
            });
    }
}


