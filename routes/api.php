<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Rutas SEO (sin middleware de autenticación)
Route::prefix('seo')->group(function () {
    // Análisis SEO
    Route::post('/analyze', [\App\Http\Controllers\Seo\SeoAnalyzerController::class, 'analyze'])
        ->name('seo.analyze');
    
    // Estado del análisis
    Route::get('/analysis/{id}', [\App\Http\Controllers\Seo\SeoAnalyzerController::class, 'getStatus'])
        ->name('seo.analysis.status');
});

// Rutas AnswerThePublic
Route::prefix('answer-the-public')->group(function () {
    Route::get('/suggestions', [AnswerThePublicController::class, 'getSuggestions']);
});