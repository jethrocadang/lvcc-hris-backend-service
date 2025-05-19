<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JobApplicantInformationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'currentAddress' => $this->current_address,
            'contactNumber' => $this->contact_number,
            'religion' => $this->religion,
            'localeAndDivision' => $this->locale_and_division,
            'servantName' => $this->servant_name,
            'servantContactNumber' =>$this->servant_contact_number,
            'dateOfBaptism' => $this->date_of_baptism,
            'churchStatus' => $this->church_status,
            'churchCommitee' => $this->church_commitee,
            'educationalAttainment' => $this->educational_attainment,
            'courseOrProgram' => $this->course_or_program,
            'schoolGraduated' => $this->school_graduated,
            'yearGraduated' => $this->year_graduated,
            'isEmployed' => $this->is_employed,
            'currentWork' => $this->current_work,
            'lastWork' => $this->last_work,
            'resume' => $this->resume,
            'transcriptOfRecords' => $this->transcript_of_records,
            'canRelocate' => $this->can_relocate,

        ];
    }
}
