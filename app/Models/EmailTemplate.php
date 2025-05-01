<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Multitenancy\Models\Concerns\UsesLandlordConnection;

class EmailTemplate extends Model
{
    use HasFactory, UsesLandlordConnection;

    protected $table = 'email_templates';
    protected $fillable = [
        'version',
        'template_type',
        'email_title',
        'email_body'
    ];

}
