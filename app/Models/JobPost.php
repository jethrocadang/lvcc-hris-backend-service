<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'work_type', 'job_type', 'title', 'description',
        'icon_url', 'status', 'location', 'schedule'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
