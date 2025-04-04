<?php

namespace App\Http\Controllers\Api\V1\Hris;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserPolicyAgreementRequest;
use App\Models\UserPolicyAgreement;
use App\Services\Hris\UserPolicyAgreementService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class UserPolicyAgreementController extends Controller
{
    use ApiResponse;

    private UserPolicyAgreementService $userPolicyAgreementService;

    public function __construct(UserPolicyAgreementService $userPolicyAgreementService)
    {
        $this->userPolicyAgreementService = $userPolicyAgreementService;
    }

    public function index()
    {
        $userPolicyAgreements = $this->userPolicyAgreementService->getUserPolicyAgreements();

        return $userPolicyAgreements->isNotEmpty()
        ? $this->successResponse('User policy agreements retrieved successfully!.', $userPolicyAgreements)
        : $this->errorResponse('No user policy agreement found', [], 404);
    }


    public function store(UserPolicyAgreementRequest $request): JsonResponse
    {
        try{
            $userPolicyAgreement = $this->userPolicyAgreementService->createUserPolicyAgreement($request);
            return $this->successResponse('User policy agreement created successfully!.', $userPolicyAgreement, 201);
        }catch(Exception $e){
            return $this->errorResponse('An error occured while creating the user policy agreement.', ['erros' => $e->getMessage()], 500);
        }
    }

    public function update(UserPolicyAgreementRequest $request, int $id): JsonResponse
    {
        try{
            $userPolicyAgreement = $this->userPolicyAgreementService->updateUserPolicyAgreement($request, $id);
            return $this->successResponse('User policy agreement updated successfully!.', $userPolicyAgreement);
        }catch(ModelNotFoundException $e){
            return $this->errorResponse($e->getMessage(), [], 404);
        }catch(Exception $e){
            return $this->errorResponse('Failed to update user policy agreement.', ['error' => $e->getMessage()], 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $this->userPolicyAgreementService->deleteUserPolicyAgreement($id);

            return $this->successResponse('User policy agreement deleted successfully!.', []);
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse($e->getMessage(), [], 404);
        } catch (Exception $e) {
            return $this->errorResponse('Failed to delete user policy agreement!.', ['error' => $e->getMessage()], 500);
        }
    }

    public function attachUserPolicyAgreement(UserPolicyAgreement $request): JsonResponse
    {
        try {
            $record = $this->userPolicyAgreementService->attachPolicyToUserAgreement($request->validated());

            return $this->successResponse('User policy agreement created successfully.', $record, 201);
        } catch (Exception $e) {
            return $this->errorResponse('Failed to attach policy to user agreement.', ['error' => $e->getMessage()], 500);
        }
    }

    public function detachUserPolicyAgreement(int $userPolicyAgreementId, int $policyId): JsonResponse
    {
        try {
            $this->userPolicyAgreementService->detachPolicyToUserAgreement($$userPolicyAgreementId, $policyId);

            return $this->successResponse('User policy agreement removed successfully.', [], 200);
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse('Policy or User Agreement not found.', [], 404);
        } catch (Exception $e) {
            return $this->errorResponse('Failed to detach Policy from user agreement.', ['error' => $e->getMessage()], 500);
        }
    }
}
