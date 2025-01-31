<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Api\V1\OauthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::controller(OauthController::class)->group( function(){
    Route::get('auth/google', 'googleLogin')->name('auth.google');
    Route::post('auth/google-callback', 'googleAuthentication')->name('auth.google-callback');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
