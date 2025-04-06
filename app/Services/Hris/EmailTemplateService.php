<?php

namespace App\Services\Hris;

use App\Http\Requests\EmailTemplateRequest;
use App\Http\Resources\EmailTemplateResource;
use App\Models\EmailTemplate;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class EmailTemplateService
{
    /**
     * Retrieve all email templates.
     *
     * @return Collection
     */
    public function getEmailTemplates(): Collection
    {
        // Make query
        $emailTemplates = EmailTemplate::all();

        // Return collection if not empty else return empty collection.
        return $emailTemplates->isNotEmpty()
            ? EmailTemplateResource::collection($emailTemplates)->collection
            : collect();
    }

    public function createEmailTemplate(EmailTemplateRequest $request): EmailTemplateResource
    {
        try {
            // Validate then create new email template
            $emailTemplate = EmailTemplate::create($request->validated());

            // Return created email template
            return new EmailTemplateResource($emailTemplate);
        } catch (Exception $e) {
            // Log errors and return the exception
            Log::error('Email template creation failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function updateEmailTemplate(EmailTemplateRequest $request, int $id): EmailTemplateResource
    {
        try {
            // Find the email template by ID
            $emailTemplate = EmailTemplate::findOrFail($id);

            // Update the email template with validated data
            $emailTemplate->update($request->validated());

            // Return updated email template
            return new EmailTemplateResource($emailTemplate);
        } catch (ModelNotFoundException $e) {
            Log::error('Email template not found', ['error' => $e->getMessage()]);
            throw $e;
        } catch (Exception $e) {
            Log::error('Email template update failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function deleteEmailTemplate(int $id): void
    {
        try {
            // Find the email template by ID
            $emailTemplate = EmailTemplate::findOrFail($id);

            // Delete the email template
            $emailTemplate->delete();
        } catch (ModelNotFoundException $e) {
            Log::error('Email template not found', ['error' => $e->getMessage()]);
            throw $e;
        } catch (Exception $e) {
            Log::error('Email template deletion failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    
}