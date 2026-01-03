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

    // Categories
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');

    // Reports
    // Reports
    Route::get('/reports', \App\Livewire\Admin\ReportQueue::class)->name('reports.index');

    // Users (Admin only)
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::post('/users/{user}/verify', [UserController::class, 'toggleVerified'])->name('users.verify');
        Route::post('/users/{user}/shadow-ban', [UserController::class, 'toggleShadowBan'])->name('users.shadow-ban');
        Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
        Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');
        Route::get('/activity-log', [App\Http\Controllers\Admin\ActivityLogController::class, 'index'])->name('activity-log.index');
    });
});
