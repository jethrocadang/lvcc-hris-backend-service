<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class JobApplicantInformation extends Model
{
    use HasFactory, UsesTenantConnection;

    protected $table = 'job_applicant_informations';

    protected $fillable = [
        'applicant_id',
        'current_address',
        'contact_number',
        'religion',
        'locale_and_division',
        'servant_name',
        'servant_contact_number',
        'date_of_baptism',
        'church_status',
        'church_commitee',
        'educational_attainment',
        'course_or_program',
        'school_graduated',
        'year_graduated',
        'is_employed',
        'current_work',
        'last_work',
        'resume',
        'transcript_of_records',
        'can_relocate',
    ];

    protected $casts = [
        'is_employed' => 'boolean',
        'can_relocate' => 'boolean',
    ];

    // Relationships

    /**
     * The applicant this information belongs to.
     */
    public function applicant()
    {
        return $this->belongsTo(JobApplicant::class, 'job_applicant_id');
    }

    // public function getActivitylogOptions(): LogOptions
    // {
    //     return LogOptions::defaults()
    //         ->logOnly($this->getFillable()) // Log all fillable, but only if changed
    //         ->logOnlyDirty()
    //         ->useLogName('job applicant information')
    //         ->setDescriptionForEvent(function (string $eventName) {
    //             $dirty = collect($this->getDirty())->except('updated_at')->toJson();

    //             return ucfirst($eventName) . " job applicant information: {$dirty}";
    //         });
    // }
}
