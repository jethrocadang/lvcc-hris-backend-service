<?php

use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    require base_path('routes/api_v1.php');
    require base_path('routes//v1/ats.php');
    require base_path('routes/v1/eth.php');
    require base_path('routes/v1/hris.php');
});
