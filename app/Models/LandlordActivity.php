<?php

namespace App\Models;

use Spatie\Activitylog\Models\Activity;
use Spatie\Multitenancy\Models\Concerns\UsesLandlordConnection;

class LandlordActivity extends Activity
{
    use UsesLandlordConnection;
    protected $connection = 'landlord';
}
