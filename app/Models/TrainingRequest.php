<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class TrainingRequest extends Model
{
    use HasFactory, UsesTenantConnection;

    protected $table = 'employee_training_requests';

    protected $fillables = [
        'employee_id',
        'supervisor_id',
        'officer_id',
        'subject',
        'body',
        'supervisor_status',
        'supervisor_reviewed_at',
        'officer_status',
        'officer_reviewed_at',
        'request_status',
    ];

    public function  employeeId()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function  supervisorId()
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    public function  officerId()
    {
        return $this->belongsTo(User::class, 'officer_id');
    }
}
