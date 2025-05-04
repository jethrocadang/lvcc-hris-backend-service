<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Department;
use App\Models\JobPosition;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class DepartmentJobPosition extends Model
{
    use HasFactory;

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
}


