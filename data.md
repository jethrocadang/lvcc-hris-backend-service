//training request request
{
    'employee_id' => 'required|exists:employees,id',
    'supervisor_id' => 'required|exists:users,id',
    'officer_id' => 'required|exists:users,id',
    'subject' => 'required|string|max:255',
    'body' => 'required|string',
    'supervisor_status' => 'in:pending,approved,rejected',
    'officer_status' => 'in:pending,approved,rejected',
    'request_status' => 'in:pending,approved,rejected',
    'supervisor_reviewed_at' => 'nullable|date',
    'officer_reviewed_at' => 'nullable|date',
}
//training request resource
{
    'id' => $this->id,
    'employeeId' => [
        'id' => $this->employee?->employee_id,
    ],
    'supervisorId' => [
        'id' => $this->supervisor?->id,
        'name' => $this->supervisor?->name,
    ],
    'officerId' => [
        'id' => $this->officer?->id,
        'name' => $this->officer?->name,
    ],
    'subject'=> $this->subject,
    'body'=> $this->body,
    'supervisorStatus'=> $this->supervisor_status,
    'supervisorReviewedAt'=> $this->supervisor_reviewed_at,
    'officerStatus'=> $this->officer_status,
    'officerReviewedAt'=> $this->officer_reviewed_at,
    'requestStatus' => $this->request_status,
    'createdAt' => $this->created_at,
    'updatedAt' => $this->updated_at,
}




