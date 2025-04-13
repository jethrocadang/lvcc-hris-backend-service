<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeInformation extends Model
{
    use HasFactory;
    protected $table = 'employee_informations';

    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'date_hired',
        'contact_number',
        'current_address',
        'permanent_address',
        'birth_date',
        'baptism_date',
        'religion',
        'gender',
        'marital_status',
        'educational_attainment',
        'license',
        'tin_number',
        'pagibig_number',
        'sss_number',
        'philhealth_number',
        'work_email',
        'personal_email',
    ];


    public function employee()
    {
        return $this->hasOne(Employee::class);
    }
}

