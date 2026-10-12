<?php

use Illuminate\Support\Facades\Route;
use Saccharine\Operations\Http\Controllers\PublicIntakeController;

/*Route::get('/', function () {
    return view('welcome');
});*/

// The public-facing form
Route::get('/intake', [PublicIntakeController::class, 'create'])->name('intake.create');

// Where the form submits its data
Route::post('/submit-intake', [PublicIntakeController::class, 'store'])->name('intake.store');
