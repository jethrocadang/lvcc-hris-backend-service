<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserAgreement extends Model
{
    use HasFactory;

    protected $fillable = [
        'policy_accepted_at',
    ];
}
