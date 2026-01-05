<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ModController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

// Installation Routes
Route::prefix('install')->name('install.')->group(function () {
    Route::get('/', [App\Http\Controllers\InstallController::class, 'welcome'])->name('welcome');
    Route::get('/requirements', [App\Http\Controllers\InstallController::class, 'requirements'])->name('requirements');
    Route::get('/permissions', [App\Http\Controllers\InstallController::class, 'permissions'])->name('permissions');
    Route::get('/environment', [App\Http\Controllers\InstallController::class, 'environment'])->name('environment');
    Route::post('/environment', [App\Http\Controllers\InstallController::class, 'saveEnvironment'])->name('environment.save');
    Route::get('/database', [App\Http\Controllers\InstallController::class, 'database'])->name('database');
    Route::post('/migrate', [App\Http\Controllers\InstallController::class, 'migrate'])->name('migrate');
    Route::get('/admin', [App\Http\Controllers\InstallController::class, 'admin'])->name('admin');
    Route::post('/admin', [App\Http\Controllers\InstallController::class, 'saveAdmin'])->name('admin.save');
    Route::get('/finish', [App\Http\Controllers\InstallController::class, 'finish'])->name('finish');
});

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/contact', [App\Http\Controllers\ContactController::class, 'index'])->name('contact.index');
Route::post('/contact', [App\Http\Controllers\ContactController::class, 'store'])->name('contact.store');

// Mods - Specific routes must come before model binding routes
Route::get('/mods', App\Livewire\ModSearch::class)->name('mods.index');
// Dedicated search route (used by homepage/category search form)
Route::get('/search', [ModController::class, 'index'])->name('mods.search');
Route::get('/mods/create', [ModController::class, 'create'])->name('mods.create')->middleware('auth');
Route::post('/mods', [ModController::class, 'store'])->name('mods.store')->middleware('auth');

// User Profiles - Specific routes must come before model binding routes
Route::middleware('auth')->group(function () {
    Route::get('/mods/my-mods', [App\Http\Controllers\ModController::class, 'myMods'])->name('mods.my-mods');
    Route::get('/mods/{mod}/edit', [App\Http\Controllers\ModController::class, 'edit'])->name('mods.edit');
    Route::put('/mods/{mod}', [App\Http\Controllers\ModController::class, 'update'])->name('mods.update');
    Route::get('/user/followings', [App\Http\Controllers\UserProfileController::class, 'followings'])->name('users.followings');
    Route::get('/user/{user}/edit', [App\Http\Controllers\UserProfileController::class, 'edit'])->name('users.edit');
    Route::put('/user/{user}', [App\Http\Controllers\UserProfileController::class, 'update'])->name('users.update');
});

// Mod routes with model binding (must come after specific routes)
Route::get('/mods/{mod}', [ModController::class, 'show'])->name('mods.show');
Route::get('/mods/{mod}/download', [App\Http\Controllers\DownloadController::class, 'index'])->name('mods.download');

// User Profiles with model binding (must come after specific routes)
Route::get('/user/{user}', [App\Http\Controllers\UserProfileController::class, 'show'])->name('users.show');

// Email Verification
Route::middleware('auth')->group(function () {
    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');
    
    Route::get('/email/verify/{id}/{hash}', function (Illuminate\Foundation\Auth\EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect()->route('home')->with('success', 'Email verified successfully!');
    })->middleware(['signed'])->name('verification.verify');

    Route::post('/email/verification-notification', function (Illuminate\Http\Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('message', 'Verification link sent!');
    })->middleware('throttle:6,1')->name('verification.send');
});

// Categories
Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('categories.show');

// Pages
Route::get('/page/{slug}', [App\Http\Controllers\PageController::class, 'show'])->name('pages.show');

// Reports
Route::post('/reports', [ReportController::class, 'store'])->name('reports.store')->middleware('auth');

// Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [\App\Http\Controllers\AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);
    Route::get('/register', [\App\Http\Controllers\AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [\App\Http\Controllers\AuthController::class, 'register']);
    
    // Password Reset
    Route::get('/forgot-password', [\App\Http\Controllers\Auth\PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [\App\Http\Controllers\Auth\PasswordResetLinkController::class, 'store'])->name('password.email');
    Route::get('/reset-password/{token}', [\App\Http\Controllers\Auth\NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password', [\App\Http\Controllers\Auth\NewPasswordController::class, 'store'])->name('password.update');
});

Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Language
Route::get('lang/{locale}', [\App\Http\Controllers\LanguageController::class, 'switch'])->name('lang.switch');

// Include Admin Routes
require __DIR__ . '/admin.php';

// Sitemap Cron
Route::get('/admin/sitemap/cron/{key}', [App\Http\Controllers\Admin\SitemapController::class, 'cron'])->name('admin.sitemap.generate.cron');

