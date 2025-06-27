<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Landing\ServicesController;
use App\Http\Controllers\Landing\PortfolioController;
use App\Http\Controllers\Landing\ContactController;
use App\Http\Controllers\Landing\SkillsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Landing Page Routes
|--------------------------------------------------------------------------
*/

// Root route - redirects to dashboard if authenticated, shows landing page if not
Route::get('/', function () {
    return auth()->check() 
        ? redirect()->route('dashboard')
        : Inertia::render('Landing/Home', [
            'auth' => ['user' => null]
        ]);
})->name('home');

// Public landing page routes
Route::get('/services', [ServicesController::class, 'index'])->name('services');
Route::get('/services/{service}', [ServicesController::class, 'show'])->name('services.show');
Route::get('/portfolio', [PortfolioController::class, 'index'])->name('portfolio');
Route::get('/portfolio/{project}', [PortfolioController::class, 'show'])->name('portfolio.show');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
Route::get('/skills/{skill}', [SkillsController::class, 'show'])->name('skills.show');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.store');
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store'])->name('register.store');
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /*
    |--------------------------------------------------------------------------
    | SEO Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('seo')->name('seo.')->group(function () {
        // SEO Dashboard
        Route::get('/dashboard', [\App\Http\Controllers\Seo\SeoDashboardController::class, 'index'])->name('dashboard');
        
        // SEO Projects
        Route::resource('projects', \App\Http\Controllers\Seo\SeoProjectController::class)->except(['show', 'edit']);
        Route::get('projects/{project}', [\App\Http\Controllers\Seo\SeoProjectController::class, 'show'])->name('projects.show');
        Route::get('projects/{project}/edit', [\App\Http\Controllers\Seo\SeoProjectController::class, 'edit'])->name('projects.edit');
        Route::get('projects/{project}/stats', [\App\Http\Controllers\Seo\SeoProjectController::class, 'stats'])->name('projects.stats');
        Route::get('projects/{project}/history', [\App\Http\Controllers\Seo\SeoProjectController::class, 'history'])->name('projects.history');
        Route::post('projects/import', [\App\Http\Controllers\Seo\SeoProjectController::class, 'import'])->name('projects.import');
        Route::get('projects/export', [\App\Http\Controllers\Seo\SeoProjectController::class, 'export'])->name('projects.export');

        // Keywords
        Route::resource('keywords', \App\Http\Controllers\Seo\KeywordController::class)
            ->only(['index', 'store', 'show', 'destroy']);
        Route::post('keywords/dataforseo', [\App\Http\Controllers\Seo\KeywordController::class, 'keywordData'])->name('keywords.dataforseo');

        // Rank Tracker
        Route::get('rank-tracker', [\App\Http\Controllers\Seo\RankTrackerController::class, 'index'])->name('rank-tracker.index');
        Route::post('rank-tracker/store', [\App\Http\Controllers\Seo\RankTrackerController::class, 'store'])->name('rank-tracker.store');
        Route::get('rank-tracker/history', [\App\Http\Controllers\Seo\RankTrackerController::class, 'history'])->name('rank-tracker.history');

        // SEO Analysis
        Route::get('analysis', [\App\Http\Controllers\Seo\SeoAnalysisController::class, 'index'])->name('analysis.index');
        Route::post('analysis/analyze', [\App\Http\Controllers\Seo\SeoAnalysisController::class, 'analyzeMetaTags'])->name('analysis.analyze');
        Route::get('analysis/{id}', [\App\Http\Controllers\Seo\SeoAnalyzerController::class, 'getStatus'])->name('analysis.get');
        Route::post('analyze', [\App\Http\Controllers\Seo\SeoAnalyzerController::class, 'analyze']);

        // Meta Tags
        Route::get('meta-tags', [\App\Http\Controllers\Seo\MetaTagsController::class, 'index'])->name('meta-tags.index');
        Route::post('meta-tags/analyze', [\App\Http\Controllers\Seo\MetaTagsController::class, 'analyze'])->name('meta-tags.analyze');

        // Open Graph
        Route::get('open-graph', [\App\Http\Controllers\Seo\OpenGraphController::class, 'index'])->name('open-graph');
        Route::post('open-graph/analyze', [\App\Http\Controllers\Seo\OpenGraphController::class, 'analyze'])->name('open-graph.analyze');
        Route::post('open-graph/analyze-url', [\App\Http\Controllers\Seo\OpenGraphController::class, 'analyzeUrl'])->name('open-graph.analyze-url');

        // Backlink Prices
        Route::prefix('backlink-prices')->name('backlink-prices.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Seo\BacklinkPriceController::class, 'index'])->name('index');
            Route::post('/', [\App\Http\Controllers\Seo\BacklinkPriceController::class, 'store'])->name('store');
            Route::get('compare', [\App\Http\Controllers\Seo\BacklinkPriceController::class, 'compare'])->name('compare');
        });
    });

    /*
    |--------------------------------------------------------------------------
    | Tool Routes
    |--------------------------------------------------------------------------
    */
    
    // AnswerThePublic
    Route::get('/answer-the-public', [\App\Http\Controllers\AnswerThePublicController::class, 'index'])->name('answer-the-public');  
    Route::post('/answer-the-public/suggestions', [\App\Http\Controllers\AnswerThePublicController::class, 'getSuggestions'])->name('answer-the-public.suggestions');

    // A/B Testing
    Route::prefix('ab-test')->name('ab-test.')->group(function () {
        Route::get('/', [\App\Http\Controllers\AbTestController::class, 'index'])->name('index');
        Route::post('/calculate', [\App\Http\Controllers\AbTestController::class, 'calculate'])->name('calculate');
    });

    // Web Traffic
    Route::get('/web-traffic', [\App\Http\Controllers\WebTrafficController::class, 'index'])->name('web-traffic.index');
    Route::get('/web-traffic/{project}', [\App\Http\Controllers\WebTrafficController::class, 'show'])->name('web-traffic.show');

    // AI Writer
    Route::get('/ai-writer', [\App\Http\Controllers\AIWriterController::class, 'index'])->name('ai-writer');
    Route::post('/ai-writer/generate', [\App\Http\Controllers\AIWriterController::class, 'generateContent']);

    // Social Media
    Route::prefix('social-media')->name('social-media.')->group(function () {
        Route::get('/', [\App\Http\Controllers\SocialMediaController::class, 'index'])->name('index');
        Route::post('/generate', [\App\Http\Controllers\SocialMediaController::class, 'generatePost'])->name('generate');
        Route::post('/schedule', [\App\Http\Controllers\SocialMediaController::class, 'schedulePost'])->name('schedule');
    });

    // Paid Ads
    Route::prefix('paid-ads')->name('paid-ads.')->group(function () {
        Route::get('/', [\App\Http\Controllers\PaidAdsController::class, 'index'])->name('index');
        Route::post('/generate', [\App\Http\Controllers\PaidAdsController::class, 'generateAdCopy'])->name('generate');
    });

    // Ads Grader
    Route::prefix('ads')->name('ads.')->middleware('throttle.campaign')->group(function () {
        Route::get('ads-grader', [\App\Http\Controllers\Ads\AdsGraderController::class, 'index'])->name('ads-grader.index');
        Route::post('ads-grader/analyze', [\App\Http\Controllers\Ads\AdsGraderController::class, 'analyze'])->name('ads-grader.analyze');
        Route::get('ads-grader/history', [\App\Http\Controllers\Ads\AdsGraderController::class, 'getAnalysisHistory'])->name('ads-grader.history');
    });

    // Mail Grader
    Route::prefix('mail')->name('mail.')->group(function () {
        Route::get('grader', [\App\Http\Controllers\MailGraderController::class, 'index'])->name('grader.index');
        Route::post('grader/analyze', [\App\Http\Controllers\MailGraderController::class, 'analyzeCampaign'])->name('grader.analyze');
    });
});

// Ruta de respaldo para Vue Router (manejo de rutas del lado del cliente)
Route::get('/{any}', function () {
    return Inertia::render('Home');
})->where('any', '.*');
