<?php

use Illuminate\Support\Facades\Route;
use SibiVarma\FormSubmissions\Http\Controllers\FormSubmissionController;

Route::prefix('api')->group(function () {
    Route::post('/form-submission', [FormSubmissionController::class, 'store']);
});