<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ModController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');

    Route::get('/users', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('admin.users.index');
    Route::post('/users/{user}/verify', [App\Http\Controllers\Admin\UserController::class, 'toggleVerified'])->name('admin.users.verify');

// Mods
Route::get('/mods', App\Livewire\ModSearch::class)->name('mods.index');
// Dedicated search route (used by homepage/category search form)
Route::get('/search', [ModController::class, 'index'])->name('mods.search');
Route::get('/mods/create', [ModController::class, 'create'])->name('mods.create')->middleware('auth');
Route::post('/mods', [ModController::class, 'store'])->name('mods.store')->middleware('auth');
Route::get('/mods/{mod}', [ModController::class, 'show'])->name('mods.show');
Route::get('/mods/{mod}/download', [ModController::class, 'download'])->name('mods.download');

// User Profiles
Route::get('/user/{user}', [App\Http\Controllers\UserProfileController::class, 'show'])->name('users.show');
Route::middleware('auth')->group(function () {
    Route::get('/user/{user}/edit', [App\Http\Controllers\UserProfileController::class, 'edit'])->name('users.edit');
    Route::put('/user/{user}', [App\Http\Controllers\UserProfileController::class, 'update'])->name('users.update');
});

// Categories
Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('categories.show');

// Reports
Route::post('/reports', [ReportController::class, 'store'])->name('reports.store')->middleware('auth');

// Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [\App\Http\Controllers\AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);
    Route::get('/register', [\App\Http\Controllers\AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [\App\Http\Controllers\AuthController::class, 'register']);
});

Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Language
Route::get('lang/{locale}', [\App\Http\Controllers\LanguageController::class, 'switch'])->name('lang.switch');

// Include Admin Routes
require __DIR__ . '/admin.php';

