<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Policy;
// use App\Models\UserAgreement;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class UserPolicyAgreement extends Model
{
    use HasFactory;

    protected $fillable = [
        'policy_id',
        // 'user_agreement_id',
        'policy_accepted_at',
    ];

    public function policy()
    {
        return $this->belongsTo(Policy::class);
    }

    // public function user_agreement()
    // {
    //     return $this->belongsTo(UserAgreement::class);
    // }
}
