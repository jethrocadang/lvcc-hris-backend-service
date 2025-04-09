<?php

namespace App\Services\Hris;

use App\Http\Requests\PolicyRequest;
use App\Http\Resources\PolicyResource;
use App\Models\Policy;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;


/**
 * Class PolicyService
 *
 * Provides business logic for managing policies.
 */
class PolicyService
{
    /**
     * Retrieve all policies.
     *
     * @return Collection A collection of policy resources.
     */
    public function getPolicies(): Collection
    {
        // TODO ADD FILTER FUNCTIONALITY
        // Retrieve all policies from the database
        $policies = Policy::all();

        // Return policies as a collection of resources if not empty, otherwise return an empty collection
        return $policies->isNotEmpty()
            ? PolicyResource::collection($policies)->collection
            : collect();
    }

    /**
     * Retrieve a policy by its ID.
     *
     * @param int $id The ID of the policy to retrieve.
     * @return PolicyResource The policy resource.
     * @throws ModelNotFoundException If the policy with the given ID is not found.
     */
    public function getPolicyById(int $id): PolicyResource
    {
        try {
            // Find the policy by ID or throw an exception if not found
            $policy = Policy::findOrFail($id);

            // Return the policy as a resource
            return new PolicyResource($policy);
        } catch (ModelNotFoundException $e) {
            // Log and rethrow a not found exception
            Log::warning("Policy with ID {$id} not found.");
            throw new ModelNotFoundException("Policy with ID {$id} not found.");
        } catch (Exception $e) {
            // Log any unexpected errors and rethrow the exception
            Log::error('Policy retrieval failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Create a new policy.
     *
     * @param PolicyRequest $policyRequest The validated request containing policy details.
     * @return PolicyResource The created policy resource.
     * @throws Exception If policy creation fails.
     */
    public function createPolicy(PolicyRequest $policyRequest): PolicyResource
    {
        try {
            if (!Auth::check()) {
                throw new Exception('User is not authenticated.');
            }

            $policyRequest->merge(['user_id' => Auth::id()]);
            // Create a new policy using validated request data
            $policy = Policy::create($policyRequest->validated());

            // Return the created policy as a resource
            return new PolicyResource($policy);
        } catch (Exception $e) {
            // Log the error and rethrow the exception
            Log::error('Policy creation failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Update an existing policy.
     *
     * @param PolicyRequest $policyRequest The validated request containing updated policy details.
     * @param int $id The ID of the policy to update.
     * @return PolicyResource The updated policy resource.
     * @throws ModelNotFoundException If the policy with the given ID is not found.
     * @throws Exception If updating the policy fails.
     */
    public function updatePolicy(PolicyRequest $policyRequest, int $id): PolicyResource
    {
        try {
            // Find the policy by ID or throw an exception if not found
            $policy = Policy::findOrFail($id);

            // Attempt to update the policy, throw an exception if it fails
            if (!$policy->update($policyRequest->validated())) {
                throw new Exception("Failed to update policy!");
            }

            // Return the updated policy as a resource
            return new PolicyResource($policy);
        } catch (ModelNotFoundException $e) {
            // Log and rethrow a not found exception
            Log::warning("Policy with ID {$id} not found.");
            throw new ModelNotFoundException("Policy with ID {$id} not found.");
        } catch (Exception $e) {
            // Log any unexpected errors and rethrow the exception
            Log::error('Policy update failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Delete a policy by its ID.
     *
     * @param int $id The ID of the policy to delete.
     * @return bool True if the deletion was successful.
     * @throws ModelNotFoundException If the policy with the given ID is not found.
     * @throws Exception If deleting the policy fails.
     */
    public function deletePolicy(int $id): bool
    {
        try {
            // Find the policy by ID or throw an exception if not found
            $policy = Policy::findOrFail($id);

            // Attempt to delete the policy, throw an exception if it fails
            if (!$policy->delete()) {
                throw new Exception("Failed to delete policy.");
            }

            // Return true to indicate successful deletion
            return true;
        } catch (ModelNotFoundException $e) {
            // Log and rethrow a not found exception
            Log::warning("Policy with ID {$id} not found!");
            throw new ModelNotFoundException("Policy with ID {$id} not found");
        } catch (Exception $e) {
            // Log any unexpected errors and rethrow the exception
            Log::error('Policy deletion failed!', ['error' => $e->getMessage()]);
            throw $e;
        }
    }
}
