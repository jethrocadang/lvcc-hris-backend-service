<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class JobApplicant extends Model
{
    use HasFactory;

    protected $connection = 'ats_db';

    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'email_verified_at',
        'status',
        'avatar_url'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

}
