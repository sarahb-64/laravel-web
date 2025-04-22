<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Controller;
use App\Http\Controllers\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Landing\HomeController;
use App\Http\Controllers\Landing\ServicesController;
use App\Http\Controllers\Landing\PortfolioController;
use App\Http\Controllers\Landing\ContactController;
use App\Http\Controllers\Landing\SkillsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Seo\SeoDashboardController;
use App\Http\Controllers\Seo\KeywordController;
use App\Http\Controllers\Seo\SeoProjectController;
use App\Http\Controllers\Seo\RankTrackerController;




Route::get('/', [HomeController::class, 'index'])->name('home');

// Rutas de la Landing Page
Route::get('/services', [ServicesController::class, 'index'])->name('services');
Route::get('/services/{service}', [ServicesController::class, 'show'])->name('services.show');
Route::get('/portfolio', [PortfolioController::class, 'index'])->name('portfolio');
Route::get('/portfolio/{project}', [PortfolioController::class, 'show'])->name('portfolio.show');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
Route::get('/skills/{skill}', [SkillsController::class, 'show'])->name('skills.show');


// Rutas SEO
Route::middleware(['auth'])->prefix('seo')->group(function () {
    Route::get('/dashboard', [SeoDashboardController::class, 'index'])->name('seo.dashboard');
    Route::resource('projects', SeoProjectController::class)->middleware('can:manage-projects');
    Route::get('projects/{project}', [SeoProjectController::class, 'show'])->name('seo.projects.show');
    Route::get('projects/{project}/edit', [SeoProjectController::class, 'edit'])->name('seo.projects.edit');
    Route::get('projects/{project}/stats', [SeoProjectController::class, 'stats'])->name('seo.projects.stats');
    Route::get('projects/{project}/history', [SeoProjectController::class, 'history'])->name('seo.projects.history');
    Route::post('projects/import', [SeoProjectController::class, 'import'])->name('seo.projects.import');
    Route::get('projects/export', [SeoProjectController::class, 'export'])->name('seo.projects.export');
});

/// Rutas de autenticación
Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.store');
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store'])->name('register.store');
});

// Rutas autenticadas
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});

// SEO Routes
Route::middleware(['auth'])->prefix('seo')->group(function () {

    // Keywords Routes
    Route::resource('keywords', KeywordController::class)
    ->only(['index', 'store', 'show', 'destroy'])
    ->names('seo.keywords');
    Route::post('/keywords/dataforseo', [KeywordController::class, 'keywordData'])->name('seo.keywords.dataforseo');
    Route::inertia('/seo/keywords/test', 'Seo/KeywordsTest')->middleware(['auth', 'verified']); //TEMPORAL
    Route::get('/seo/dashboard', [SeoDashboardController::class, 'index'])->name('seo.dashboard');

    // RankTracker Routes
    Route::get('/rank-tracker', [RankTrackerController::class, 'index'])->name('rank-tracker.index');
    Route::post('/rank-tracker/store', [RankTrackerController::class, 'store'])->name('rank-tracker.store');
    Route::get('/rank-tracker/history', [RankTrackerController::class, 'history'])->name('rank-tracker.history');
});

// Project Management Routes
Route::middleware(['auth'])->prefix('projects')->group(function () {
    Route::get('/', [SeoProjectController::class, 'index'])->name('projects.index');
    Route::get('create', [SeoProjectController::class, 'create'])->name('projects.create');
    Route::post('/', [SeoProjectController::class, 'store'])->name('projects.store');
    Route::get('{project}/edit', [SeoProjectController::class, 'edit'])->name('projects.edit');
    Route::put('{project}', [SeoProjectController::class, 'update'])->name('projects.update');
    Route::delete('{project}', [SeoProjectController::class, 'destroy'])->name('projects.destroy');
});

// Backlink Price Routes
Route::middleware(['auth'])->prefix('seo')->group(function () {
    Route::prefix('backlink-prices')->group(function () {
        Route::get('/', [BacklinkPriceController::class, 'index'])->name('seo.backlink-prices.index');
        Route::post('/', [BacklinkPriceController::class, 'store'])->name('seo.backlink-prices.store');
        Route::get('compare', [BacklinkPriceController::class, 'compare'])->name('seo.backlink-prices.compare');
    });
});