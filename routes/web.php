<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactFormController;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/contact/{slug}', [ContactFormController::class, 'show'])
    ->name('contact-form.show');
Route::post('/contact/{slug}', [ContactFormController::class, 'submit'])
    ->name('contact-form.submit');