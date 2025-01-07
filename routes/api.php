<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OauthController;

Route::controller(OauthController::class)->group( function(){
    Route::get('auth/google', 'googleLogin');
    Route::get('auth/google-callback', 'googleAuthentication');
});
