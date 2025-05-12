<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Employee extends Model
{
    use HasFactory, LogsActivity;
    protected $table = 'employees';

    protected $connection = 'landlord';

    protected $fillable = [
        'user_id',
        'employee_id',
        'department_position_id',
        'employee_information_id',
        'employee_type',
        'employment_status',
        'employment_category',
        'employee_status',
        'employment_end_date',
        'latest_position_designation',
        'work_schedule',
    ];

    public function user()
    {
        return $this->belongsTo(User::class)->select('id', 'name', 'avatar_url');
    }

    public function employeeInformation()
    {
        return $this->belongsTo(EmployeeInformation::class);
    }

    public function departmentJobPosition()
    {
        return $this->belongsTo(DepartmentJobPosition::class, 'department_position_id');
    }

    /**
     * Define Spatie's logging options.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly($this->getFillable()) // Log all fillable, but only if changed
            ->logOnlyDirty()
            ->useLogName('employee')
            ->setDescriptionForEvent(function (string $eventName) {
                $dirty = collect($this->getDirty())->except('updated_at')->toJson();
    
                return ucfirst($eventName) . " employee: {$dirty}";
            });
    }
}
