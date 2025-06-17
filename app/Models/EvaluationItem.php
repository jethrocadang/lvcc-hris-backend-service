<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class EvaluationItem extends Model
{
    use HasFactory, UsesTenantConnection, LogsActivity;

    protected $connection = 'tenant';

    protected $fillable = [
        'evaluation_category_id',
        'question',
        'sequence_order'
    ];

    public function category()
    {
        return $this->belongsTo(EvaluationCategory::class, 'evaluation_category_id');
    }

    public function responses()
    {
        return $this->hasMany(EvaluationResponse::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly($this->getFillable())
            ->logOnlyDirty()
            ->useLogName('evaluation item')
            ->setDescriptionForEvent(function (string $eventName) {
                $dirty = collect($this->getDirty())->except('updated_at')->toJson();

                return ucfirst($eventName) . " evaluation item: {$dirty}";
            });
    }
}
