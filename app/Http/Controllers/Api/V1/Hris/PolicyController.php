<?php

namespace App\Http\Controllers\Api\V1\Hris;

use App\Http\Requests\PolicyRequest;
use App\Traits\ApiResponse;
use App\Http\Controllers\Controller;
use App\Services\Hris\PolicyService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

/**
 * Class PolicyController
 *
 * Handles the management of policies such as Data Privacy or User Policy Agreements.
 */
class PolicyController extends Controller
{
    use ApiResponse;

    /**
     * @var PolicyService The policy service instance used for policy-related operations.
     */
    private PolicyService $policyService;

    /**
     * PolicyController constructor.
     *
     * @param PolicyService $policyService Injects the policy service to handle business logic.
     */
    public function __construct(PolicyService $policyService)
    {
        $this->policyService = $policyService;
    }

    /**
     * Create a new policy.
     *
     * @param PolicyRequest $request The validated request containing policy details.
     * @return \Illuminate\Http\JsonResponse JSON response with success or error message.
     */
    public function store(PolicyRequest $request)
    {
        try {
            // Call service method to create a new policy
            $policy = $this->policyService->createPolicy($request);

            // Return success response with created policy data
            return $this->successResponse('Policy created successfully!', $policy, 201);
        } catch (Exception $e) {
            // Handle any unexpected exceptions and return an error response
            return $this->errorResponse('An error occurred while creating policy.', [
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Retrieve all policies.
     *
     * @return \Illuminate\Http\JsonResponse JSON response containing policies or an error message.
     */
    public function index()
    {
        // Fetch all policies from the service
        $policies = $this->policyService->getPolicies();

        // Check if policies exist and return appropriate response
        return $policies->isNotEmpty()
            ? $this->successResponse('Policies retrieved successfully!', $policies)
            : $this->errorResponse('No policies found!', [], 404);
    }

    /**
     * Retrieve a specific policy by its ID.
     *
     * @param int $id The ID of the policy to retrieve.
     * @return \Illuminate\Http\JsonResponse JSON response with policy data or an error message.
     */
    public function show($id)
    {
        try {
            // Attempt to fetch the policy by ID
            $policy = $this->policyService->getPolicyById($id);

            // Return success response with policy data
            return $this->successResponse('Policy retrieved successfully!', $policy);
        } catch (ModelNotFoundException $e) {
            // Handle case where policy with given ID is not found
            return $this->errorResponse('Policy not found.', [], 404);
        } catch (Exception $e) {
            // Handle any unexpected exceptions during retrieval
            return $this->errorResponse('Failed to retrieve policy!', [
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update an existing policy.
     *
     * @param PolicyRequest $request The validated request containing updated policy details.
     * @param int $id The ID of the policy to be updated.
     * @return \Illuminate\Http\JsonResponse JSON response with success or error message.
     */
    public function update(PolicyRequest $request, $id)
    {
        try {
            // Attempt to update the policy with the provided ID
            $policy = $this->policyService->updatePolicy($request, $id);

            // Return success response with updated policy data
            return $this->successResponse('Policy updated successfully!', $policy);
        } catch (ModelNotFoundException $e) {
            // Handle case where policy with given ID is not found
            return $this->errorResponse('Policy not found.', [], 404);
        } catch (Exception $e) {
            // Handle any unexpected exceptions during update
            return $this->errorResponse('Failed to update policy!', [
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a policy by its ID.
     *
     * @param int $id The ID of the policy to delete.
     * @return \Illuminate\Http\JsonResponse JSON response with success or error message.
     */
    public function destroy($id)
    {
        try {
            // Attempt to delete the policy with the given ID
            $this->policyService->deletePolicy($id);

            // Return success response indicating policy was deleted
            return $this->successResponse('Policy deleted successfully!', []);
        } catch (ModelNotFoundException $e) {
            // Handle case where policy with given ID is not found
            return $this->errorResponse('Policy not found.', [], 404);
        } catch (Exception $e) {
            // Handle any unexpected exceptions during deletion
            return $this->errorResponse('Failed to delete policy!', [
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
