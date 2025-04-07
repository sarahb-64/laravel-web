<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\TrackController;
use Illuminate\Support\Facades\Mail;
use App\Notifications\PositionAlert;
use App\Notifications\TestEmailNotification;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

// Las rutas de autenticación de Laravel Breeze
require __DIR__.'/auth.php';

// Ruta para mostrar el formulario de búsqueda
Route::get('/search', [SearchController::class, 'index']);

// Ruta para procesar la búsqueda y rastrear la posición de la palabra clave
Route::post('/track-position', [SearchController::class, 'trackPosition'])->name('track.position');

// Ruta para gestionar las palabras clave
Route::resource('keywords', KeywordController::class);

Route::get('/test-email', function () {
    Mail::raw('This is a test email', function ($message) {
        $message->to('test@example.com')
                ->subject('Test Email');
    });

    return 'Email sent';
});


Route::get('/test-email', function () {
    // Trigger the notification (send email) to a user
    // Assuming you have a User model with an email address
    $user = \App\Models\User::first(); // Use the first user for testing
    $user->notify(new TestEmailNotification());

    return 'Email sent successfully!';
});

Route::get('/content-generator', [ContentController::class, 'index']);
Route::post('/generate-content', [ContentController::class, 'generateContent']);
