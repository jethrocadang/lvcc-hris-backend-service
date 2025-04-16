<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;
    protected $table = 'employees';

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
}
