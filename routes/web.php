<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ArticleController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;

// use Inertia\Inertia;


// Article Route

// API endpoint
Route::get('/api', [ArticleController::class, 'index']);

// Test Page to show all news
Route::get('/', [ArticleController::class, 'allNews']);

// Fetch page
Route::get('/fetch', [Articlecontroller::class, 'fetch']);

// Fetch articles and store them
Route::get('/fetchNow', [Articlecontroller::class, 'fetchNow']);

// Authentication Routes
// Route::get('/', function () {
//     return Inertia::render('', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });

// User Dashboard/profile routes
Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
