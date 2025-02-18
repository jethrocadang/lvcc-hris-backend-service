<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Position extends Model
{
    use HasFactory;

    protected $fillable = ['position_title',
                           'description'];
}

    // public function department_position(): BelongsTo
    // {
    //     return $this->belongsTo(DepartmentPosition::class);
    // }