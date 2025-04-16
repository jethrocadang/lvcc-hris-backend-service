<?php

namespace App\Http\Controllers\Api\V1\Hris;


use App\Models\EmailTemplate;
use App\Http\Controllers\Controller;
use App\Http\Requests\EmailTemplateRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Traits\ApiResponse;
use Exception;
use App\Services\Hris\EmailTemplateService;


class EmailTemplateController extends Controller
{
    use ApiResponse;

    private EmailTemplateService $emailTemplateService;

    public function __construct(EmailTemplateService $emailTemplateService)
    {
        $this->emailTemplateService = $emailTemplateService;
    }

    public function index(): JsonResponse
    {
        $emailTemplates = $this->emailTemplateService->getEmailTemplates();

        return $emailTemplates->isNotEmpty()
            ? $this->successResponse('Email templates retrieved successfully!', $emailTemplates)
            : $this->errorResponse('No email templates found', [], 404);
    }

    public function show(int $id): JsonResponse
    {
        try {
            $emailTemplate = $this->emailTemplateService->getEmailTemplateById($id);
            return $this->successResponse('Email template retrieved successfully!', $emailTemplate);
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse($e->getMessage(), [], 404);
        } catch (Exception $e) {
            return $this->errorResponse('Failed to retrieve email template!', ['error' => $e->getMessage()], 500);
        }
    }

    public function store(EmailTemplateRequest $request): JsonResponse
    {
        try {
            $emailTemplate = $this->emailTemplateService->createEmailTemplate($request);
            return $this->successResponse('Email template created successfully!', $emailTemplate, 201);
        } catch (Exception $e) {
            return $this->errorResponse('An error occurred while creating the email template.', ['error' => $e->getMessage()], 500);
        }
    }

    public function update(EmailTemplateRequest $request, int $id): JsonResponse
    {
        try {
            $emailTemplate = $this->emailTemplateService->updateEmailTemplate($request, $id);
            return $this->successResponse('Email template updated successfully!', $emailTemplate);
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse($e->getMessage(), [], 404);
        } catch (Exception $e) {
            return $this->errorResponse('Failed to update email template!', ['error' => $e->getMessage()], 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            // Find the email template by ID
            $this->emailTemplateService->deleteEmailTemplate($id);

            // Return success response
            return $this->successResponse('Email template deleted successfully!', []);
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse($e->getMessage(), [], 404);
        } catch (Exception $e) {
            return $this->errorResponse('Failed to delete department!', ['error' => $e->getMessage()], 500);
        }
    }
}
