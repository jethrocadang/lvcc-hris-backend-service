<?php

namespace App\Http\Controllers\Api\V1\Ats;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ats\AtsEmailTemplateRequest;
use App\Http\Resources\AtsEmailTemplateResource;
use App\Services\Ats\AtsEmailTemplateService;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AtsEmailtemplateController extends Controller
{
    use ApiResponse;

    private AtsEmailTemplateService $atsEmailTemplateService;

    public function __construct(AtsEmailTemplateService $atsEmailTemplateService)
    {
        $this->atsEmailTemplateService = $atsEmailTemplateService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $filters = $request->all();
        $perPage = (int) ($filters['per_page'] ?? 10);

        $emailTemplates = $this->atsEmailTemplateService->getAtsEmailTemplates($filters, $perPage);

        $meta = [
            'current_page' => $emailTemplates->currentPage(),
            'last_page' => $emailTemplates->lastPage(),
            'total' => $emailTemplates->total(),
        ];

        return $this->successResponse(
            'ATS email templates retrieved successfully!',
            AtsEmailTemplateResource::collection($emailTemplates),
            200,
            $meta
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }


    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        try {
            $emailTemplate = $this->atsEmailTemplateService->getAtsEmailTemplateById($id);

            if (!$emailTemplate) {
                return $this->errorResponse("Email template not found", [], 404);
            }

            return $this->successResponse("Success", $emailTemplate, 200);
        } catch (Exception $e) {
            return $this->errorResponse("Error", [$e->getMessage()], 500);
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(AtsEmailTemplateRequest $request, int $id)
    {
        try {
            $updatedTemplate = $this->atsEmailTemplateService->updateAtsEmail($id, $request->validated());

            if (!$updatedTemplate) {
                return $this->errorResponse("Update failed or email template not found", [], 404);
            }

            return $this->successResponse("Updated successfully", $updatedTemplate, 200);
        } catch (Exception $e) {
            return $this->errorResponse("Error", [$e->getMessage()], 500);
        }
    }
}
