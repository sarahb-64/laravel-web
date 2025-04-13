<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\AuthenticatedSessionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\HomeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [Landing\HomeController::class, 'index'])->name('home');

// Rutas de la Landing Page
Route::get('/services', [Landing\ServicesController::class, 'index'])->name('services');
Route::get('/portfolio', [Landing\PortfolioController::class, 'index'])->name('portfolio');
Route::get('/contact', [Landing\ContactController::class, 'index'])->name('contact');

// Rutas de autenticación
Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.store');
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store'])->name('register.store');
});

// Rutas de la Plataforma SEO
Route::middleware(['auth:sanctum', 'verified'])->prefix('seo')->group(function () {
    Route::get('/dashboard', [Seo\DashboardController::class, 'index'])->name('seo.dashboard');
    
    Route::resource('projects', Seo\ProjectController::class)->middleware('can:manage-projects');
    Route::get('projects/{project}', [Seo\ProjectController::class, 'show'])->name('seo.projects.show');
    Route::get('projects/{project}/edit', [Seo\ProjectController::class, 'edit'])->name('seo.projects.edit');
    Route::get('projects/{project}/stats', [Seo\ProjectController::class, 'stats'])->name('seo.projects.stats');
    Route::get('projects/{project}/history', [Seo\ProjectController::class, 'history'])->name('seo.projects.history');
    Route::post('projects/import', [Seo\ProjectController::class, 'import'])->name('seo.projects.import');
    Route::get('projects/export', [Seo\ProjectController::class, 'export'])->name('seo.projects.export');
    
    Route::resource('keywords', Seo\KeywordController::class)->middleware('can:manage-keywords');
});