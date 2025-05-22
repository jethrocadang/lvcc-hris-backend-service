<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesLandlordConnection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class InterviewScheduleSlot extends Model
{
    use HasFactory, UsesLandlordConnection, LogsActivity;

    protected $fillable = [
        // commented out admin to avoid error as of now.
        'admin',
        'scheduled_date',
    ];

    /**
     * Get the admin (user) who created the schedule slot.
     */
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin');
    }

    public function timeSlots()
    {
        return $this->hasMany(InterviewScheduleTimeSlot::class, 'interview_slot_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly($this->getFillable()) // Log all fillable, but only if changed
            ->logOnlyDirty()
            ->useLogName('interview schedule slot')
            ->setDescriptionForEvent(function (string $eventName) {
                $dirty = collect($this->getDirty())->except('updated_at')->toJson();

                return ucfirst($eventName) . " interview schedule slot: {$dirty}";
            });
    }
}
