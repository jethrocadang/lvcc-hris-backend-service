<?php

namespace App\Services\Eth;

use App\Http\Requests\Eth\EvaluationFormRequest;
use App\Http\Resources\Eth\EvaluationFormResource;
use App\Models\EvaluationForm;
use App\Models\EvaluationCategory;
use App\Models\EvaluationItem;
use App\Models\EvaluationResponse;
use App\Models\EvaluationComment;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Exception;

class EvaluationFormService
{
    /**
     * Get a paginated list of evaluation forms
     */
    public function getEvaluationForms(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        try {
            return QueryBuilder::for(EvaluationForm::class)
                ->allowedFilters([
                    AllowedFilter::partial('title'),
                    AllowedFilter::exact('employee_training_course_id'),
                    AllowedFilter::exact('is_active'),
                ])
                ->allowedSorts(['created_at', 'title'])
                ->with(['trainingCourse'])
                ->paginate($perPage)
                ->appends($filters);
        } catch (Exception $e) {
            Log::error('Failed to retrieve evaluation forms', ['error' => $e->getMessage()]);
            return new LengthAwarePaginator([], 0, $perPage);
        }
    }

    /**
     * Get a specific evaluation form by ID with all related data
     */
    public function getEvaluationFormById(int $id)
    {
        try {
            $evaluationForm = EvaluationForm::with([
                'categories.items',
                'trainingCourse'
            ])->findOrFail($id);

            return new EvaluationFormResource($evaluationForm);
        } catch (ModelNotFoundException $e) {
            Log::error('Evaluation form not found.', ['error' => $e->getMessage()]);
            throw $e;
        } catch (Exception $e) {
            Log::error('Evaluation form retrieval failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Create a new evaluation form with categories and items
     */
    public function createEvaluationForm(EvaluationFormRequest $request): EvaluationFormResource
    {
        DB::beginTransaction();

        try {
            $data = $request->validated();

            // Create the evaluation form
            $evaluationForm = EvaluationForm::create([
                'employee_training_course_id' => $data['employee_training_course_id'],
                'title' => $data['title'],
                'is_active' => $data['is_active'] ?? true,
            ]);

            // Create categories if provided
            if (!empty($data['categories'])) {
                foreach ($data['categories'] as $index => $categoryData) {
                    $category = $evaluationForm->categories()->create([
                        'title' => $categoryData['title'],
                        'sequence_order' => $index + 1,
                    ]);

                    // Create items for this category if provided
                    if (!empty($categoryData['items'])) {
                        foreach ($categoryData['items'] as $itemIndex => $itemData) {
                            $category->items()->create([
                                'question' => $itemData['question'],
                                'sequence_order' => $itemIndex + 1,
                            ]);
                        }
                    }
                }
            }

            DB::commit();

            return new EvaluationFormResource(
                EvaluationForm::with(['categories.items', 'trainingCourse'])->find($evaluationForm->id)
            );
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Evaluation form creation failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Update an existing evaluation form and its components
     */
    public function updateEvaluationForm(EvaluationFormRequest $request, int $id): EvaluationFormResource
    {
        DB::beginTransaction();

        try {
            $data = $request->validated();
            $evaluationForm = EvaluationForm::findOrFail($id);

            // Update basic form information
            $evaluationForm->update([
                'employee_training_course_id' => $data['employee_training_course_id'],
                'title' => $data['title'],
                'is_active' => $data['is_active'] ?? $evaluationForm->is_active,
            ]);

            // Update categories if provided
            if (!empty($data['categories'])) {
                // Get existing category IDs to track which ones to delete later
                $existingCategoryIds = $evaluationForm->categories->pluck('id')->toArray();
                $updatedCategoryIds = [];

                foreach ($data['categories'] as $index => $categoryData) {
                    // Update or create category
                    if (!empty($categoryData['id'])) {
                        $category = EvaluationCategory::findOrFail($categoryData['id']);
                        $category->update([
                            'title' => $categoryData['title'],
                            'sequence_order' => $index + 1,
                        ]);
                        $updatedCategoryIds[] = $category->id;
                    } else {
                        $category = $evaluationForm->categories()->create([
                            'title' => $categoryData['title'],
                            'sequence_order' => $index + 1,
                        ]);
                        $updatedCategoryIds[] = $category->id;
                    }

                    // Update items for this category if provided
                    if (!empty($categoryData['items'])) {
                        // Get existing item IDs to track which ones to delete later
                        $existingItemIds = $category->items->pluck('id')->toArray();
                        $updatedItemIds = [];

                        foreach ($categoryData['items'] as $itemIndex => $itemData) {
                            // Update or create item
                            if (!empty($itemData['id'])) {
                                $item = EvaluationItem::findOrFail($itemData['id']);
                                $item->update([
                                    'question' => $itemData['question'],
                                    'sequence_order' => $itemIndex + 1,
                                ]);
                                $updatedItemIds[] = $item->id;
                            } else {
                                $item = $category->items()->create([
                                    'question' => $itemData['question'],
                                    'sequence_order' => $itemIndex + 1,
                                ]);
                                $updatedItemIds[] = $item->id;
                            }
                        }

                        // Delete items that weren't updated or created
                        $itemsToDelete = array_diff($existingItemIds, $updatedItemIds);
                        if (!empty($itemsToDelete)) {
                            EvaluationItem::whereIn('id', $itemsToDelete)->delete();
                        }
                    }
                }

                // Delete categories that weren't updated or created
                $categoriesToDelete = array_diff($existingCategoryIds, $updatedCategoryIds);
                if (!empty($categoriesToDelete)) {
                    EvaluationCategory::whereIn('id', $categoriesToDelete)->delete();
                }
            }

            DB::commit();

            return new EvaluationFormResource(
                EvaluationForm::with(['categories.items', 'trainingCourse'])->find($evaluationForm->id)
            );
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Evaluation form update failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Delete an evaluation form and its related components
     */
    public function deleteEvaluationForm(int $id): bool
    {
        DB::beginTransaction();

        try {
            $evaluationForm = EvaluationForm::findOrFail($id);

            // Delete all related data
            // First delete items within categories
            foreach ($evaluationForm->categories as $category) {
                $category->items()->delete();
            }

            // Delete categories
            $evaluationForm->categories()->delete();

            // Delete responses and comments
            $evaluationForm->responses()->delete();
            $evaluationForm->comments()->delete();

            // Delete the form itself
            $evaluationForm->delete();

            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Evaluation form deletion failed.', ['error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Submit evaluation responses for a user
     */
    public function submitEvaluationResponses(array $data, int $employeeId): bool
    {
        DB::beginTransaction();

        try {
            $formId = $data['evaluation_form_id'];
            $courseId = $data['training_course_id'];
            $now = now();

            // Process and save responses
            foreach ($data['responses'] as $response) {
                EvaluationResponse::create([
                    'employee_id' => $employeeId,
                    'evaluation_form_id' => $formId,
                    'training_course_id' => $courseId,
                    'evaluation_item_id' => $response['evaluation_item_id'],
                    'score' => $response['score'],
                    'submitted_at' => $now,
                ]);
            }

            // Save comment if provided
            if (!empty($data['comment'])) {
                EvaluationComment::create([
                    'employee_id' => $employeeId,
                    'evaluation_form_id' => $formId,
                    'training_course_id' => $courseId,
                    'comment' => $data['comment'],
                    'submitted_at' => $now,
                ]);
            }

            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Evaluation response submission failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Get evaluation responses for a specific form
     */
    public function getEvaluationResponses(int $formId, array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        try {
            $evaluationForm = EvaluationForm::findOrFail($formId);

            // Group responses by employee
            $responses = QueryBuilder::for(EvaluationResponse::class)
                ->select('employee_id')
                ->where('evaluation_form_id', $formId)
                ->groupBy('employee_id')
                ->with([
                    'employee',
                    'form',
                    'trainingCourse'
                ])
                ->paginate($perPage)
                ->appends($filters);

            return $responses;
        } catch (Exception $e) {
            Log::error('Failed to retrieve evaluation responses', ['error' => $e->getMessage()]);
            return new LengthAwarePaginator([], 0, $perPage);
        }
    }

    /**
     * Get evaluation response details for a specific employee and form
     */
    public function getEmployeeEvaluationResponses(int $formId, int $employeeId)
    {
        try {
            $responses = EvaluationResponse::where([
                'evaluation_form_id' => $formId,
                'employee_id' => $employeeId
            ])->with(['item', 'employee', 'form'])->get();

            $comment = EvaluationComment::where([
                'evaluation_form_id' => $formId,
                'employee_id' => $employeeId
            ])->first();

            return [
                'responses' => $responses,
                'comment' => $comment
            ];
        } catch (Exception $e) {
            Log::error('Failed to retrieve employee evaluation responses', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Get evaluation forms by training course ID
     */
    public function getEvaluationFormByCourseId(int $courseId)
    {
        try {
            $evaluationForms = EvaluationForm::where('employee_training_course_id', $courseId)
                ->with(['categories.items', 'trainingCourse'])
                ->get();

            if ($evaluationForms->isEmpty()) {
                throw new ModelNotFoundException("No evaluation forms found for course ID: {$courseId}");
            }

            return EvaluationFormResource::collection($evaluationForms);
        } catch (ModelNotFoundException $e) {
            Log::error('Evaluation forms not found for course.', ['error' => $e->getMessage()]);
            throw $e;
        } catch (Exception $e) {
            Log::error('Evaluation form retrieval by course ID failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }
}
