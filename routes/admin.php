<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ModController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:admin|moderator'])->name('admin.')->prefix('admin')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Mod Management
    Route::get('/mods', [ModController::class, 'index'])->name('mods.index');
    Route::get('/mods/{mod}/edit', [ModController::class, 'edit'])->name('mods.edit');
    Route::put('/mods/{mod}', [ModController::class, 'update'])->name('mods.update');
    Route::delete('/mods/{mod}', [ModController::class, 'destroy'])->name('mods.destroy');

    // Categories
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');

    // Reports
    Route::get('/reports', \App\Livewire\Admin\ReportQueue::class)->name('reports.index');

    // Comments
    Route::get('/comments', \App\Livewire\Admin\CommentQueue::class)->name('comments.index');

    // Users (Admin only)
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::post('/users/{user}/verify', [UserController::class, 'toggleVerified'])->name('users.verify');
        Route::post('/users/{user}/shadow-ban', [UserController::class, 'toggleShadowBan'])->name('users.shadow-ban');
        Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
        Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');
        Route::get('/ad-settings', \App\Livewire\Admin\AdSettings::class)->name('ad-settings.index');
        Route::get('/email-settings', \App\Livewire\Admin\EmailSettings::class)->name('email-settings.index');
        Route::get('/pages', \App\Livewire\Admin\PageManager::class)->name('pages.index');
        Route::get('/activity-log', [App\Http\Controllers\Admin\ActivityLogController::class, 'index'])->name('activity-log.index');
        
        // Contact Messages
        Route::get('/contacts', [App\Http\Controllers\Admin\ContactController::class, 'index'])->name('contacts.index');
        Route::get('/contacts/{message}', [App\Http\Controllers\Admin\ContactController::class, 'show'])->name('contacts.show');
        Route::delete('/contacts/{message}', [App\Http\Controllers\Admin\ContactController::class, 'destroy'])->name('contacts.destroy');

        // Sitemap
        Route::get('/sitemap', [App\Http\Controllers\Admin\SitemapController::class, 'index'])->name('sitemap.index');
        Route::post('/sitemap', [App\Http\Controllers\Admin\SitemapController::class, 'generate'])->name('sitemap.generate');

        // Analytics (New)
        Route::get('/analytics', [App\Http\Controllers\Admin\AnalyticsController::class, 'index'])->name('analytics.index');
    });
});
