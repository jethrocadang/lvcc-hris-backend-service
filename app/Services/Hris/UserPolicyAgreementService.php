<?php

namespace App\Services\Hris;

use App\Http\Requests\UserPolicyAgreementRequest;
use App\Http\Resources\UserPolicyAgreementResource;
use App\Models\UserPolicyAgreement;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class UserPolicyAgreementService
{
    public function getUserPolicyAgreements(): Collection
    {
        $userPolicyAgreements = UserPolicyAgreement::all();

        return $userPolicyAgreements->isNotEmpty()
            ? UserPolicyAgreementResource::collection($userPolicyAgreements)->collection
            : collect();
    }

    public function getUserPolicyAgreementById(int $id): UserPolicyAgreementResource
    {
        try {
            $userPolicyAgreement = UserPolicyAgreement::findOrFail($id);

            return new UserPolicyAgreementResource($userPolicyAgreement);
        } catch (ModelNotFoundException $e) {
            Log::warning("User policy agreement with ID {$id} not found.");
            throw new ModelNotFoundException("User policy agreement with ID {$id} not found.");
        } catch (Exception $e) {
            Log::error('User policy agreement retrieval failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function createUserPolicyAgreement(UserPolicyAgreementRequest $request): UserPolicyAgreementResource
    {
        try {
            $userPolicyAgreement = UserPolicyAgreement::create($request->validated());

            return new UserPolicyAgreementResource($userPolicyAgreement);
        }catch(Exception $e){
            Log::error('User policy agreement creation failed'. ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function updateUserPolicyAgreement(UserPolicyAgreementRequest $request, int $id): UserPolicyAgreementResource
    {
        try{
            $userPolicyAgreement = UserPolicyAgreement::findOrFail($id);

            if(!$userPolicyAgreement->update($request->validated())) {
                throw new Exception('Failed to update user policy agreement.');
            }

            return new UserPolicyAgreementResource($userPolicyAgreement);
        }catch(ModelNotFoundException $e) {
            Log::warning("User policy agreement with ID {$id} not found.");
            throw new ModelNotFoundException("User policy agreement with ID {$id} not found.");
        }catch(Exception $e){
            Log::error('User policy agreement update failed', ['error' => $e->getMessage()]);
            throw new Exception('An error occured while updating the user policy agreement.');
        }
    }

    public function deleteUserPolicyAgreement(int $id): bool
    {
        try {

            $userPolicyAgreement = UserPolicyAgreement::findOrFail($id);

            if (!$userPolicyAgreement->delete()) {
                throw new Exception("Failed to delete user policy agreement.");
            }
            return true;
        } catch (ModelNotFoundException $e) {
            Log::warning("User policy agreement {$id} not found.");
            throw new ModelNotFoundException("User policy agreement {$id} not found.");
        } catch (Exception $e) {
            Log::error('Department deletion failed', ['error' => $e->getMessage()]);
            throw new Exception("An error occurred while deleting the user policy agreement.");
        }
    }

    public function getAll()
    {
        return UserPolicyAgreement::with('policies')->get();
    }

    public function attachPolicyToUserAgreement(array $data)
    {
        try{
            $userPolicyAgreement = UserPolicyAgreement::findOrFail($data['user_policy_agreement_id']);
            $userPolicyAgreement->policy()->attach($data['policy_id']);

            return[
                'user_policy_agreement_id' => $userPolicyAgreement->id,
                'policy_id' => $data['policy_id'],
            ];
        }catch(ModelNotFoundException $e){
            Log::warning('Policy or User agreement not found.', $data);
            throw new ModelNotFoundException('Policy or User agreement not found.');
        }catch(Exception $e){
            Log::error('Failed to attach Policy to User Agrement.'. ['error' => $e->getMessage()]);
            throw new Exception('Failed to attach Policy to User Agrement.');
        }
    }

    public function detachPolicyToUserAgreement(int $userPolicyAgreementId, int $policyId): bool
    {
        try {
            $userPolicyAgreement = UserPolicyAgreement::findOrFail($userPolicyAgreementId);
            $userPolicyAgreement->policy()->detach($policyId);

            return true;
        } catch (ModelNotFoundException $e) {
            Log::warning("Department or Job Position not found.", ['department_id' => $userPolicyAgreementId, 'job_position_id' => $policyId]);
            throw new ModelNotFoundException('Department or Job Position not found.');
        } catch (Exception $e) {
            Log::error('Failed to detach job position from department.', ['error' => $e->getMessage()]);
            throw new Exception('Failed to detach job position from department.');
        }
    }
}