<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DepartmentPosition extends Model
{
    use HasFactory;

    protected $fillable = ['department_id', 'position_id'];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function position()
    {
        return $this->belongsTo(Position::class);
    }
}


