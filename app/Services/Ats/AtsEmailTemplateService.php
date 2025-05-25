<?php

namespace App\Services\Ats;

use App\Models\AtsEmailTemplate;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Log;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class AtsEmailTemplateService
{
    public function getAtsEmailTemplates(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        try {
            return QueryBuilder::for(AtsEmailTemplate::class)
                ->allowedFilters([
                    AllowedFilter::exact('type'),
                ])
                ->allowedSorts(['created_at', 'type'])
                ->paginate($perPage)
                ->appends($filters);
        } catch (Exception $e) {
            Log::error('Failed to retrieve ATS email templates', ['error' => $e->getMessage()]);
            return new LengthAwarePaginator([], 0, $perPage);
        }
    }
}
