<?php

use Illuminate\Support\Facades\Route;


Route::middleware('tenant')->group(function () {

    Route::middleware(['auth.jwt.tenant', 'auth.jwt'])->group(function () {

    });

});