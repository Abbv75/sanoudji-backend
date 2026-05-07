<?php

use App\Http\Controllers\SwaggerController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Documentation Swagger UI
Route::get('/docs', [SwaggerController::class, 'ui'])->name('swagger.ui');
Route::get('/api/docs/spec', [SwaggerController::class, 'spec'])->name('swagger.spec');
