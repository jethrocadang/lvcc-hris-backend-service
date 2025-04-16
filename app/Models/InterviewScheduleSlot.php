<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesLandlordConnection;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InterviewScheduleSlot extends Model
{
    use HasFactory, UsesLandlordConnection;

    protected $fillable = [
        // commented out admin to avoid error as of now.
        'admin',
        'scheduled_date',
        'start_time',
        'slot_status',
    ];

    /**
     * Get the admin (user) who created the schedule slot.
     */
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin');
    }
}
