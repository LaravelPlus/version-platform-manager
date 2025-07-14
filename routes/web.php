<?php

use Illuminate\Support\Facades\Route;
use LaravelPlus\VersionPlatformManager\Http\Controllers\VersionController;
use LaravelPlus\VersionPlatformManager\Http\Controllers\WhatsNewController;
use LaravelPlus\VersionPlatformManager\Http\Controllers\UserController;
use LaravelPlus\VersionPlatformManager\Http\Controllers\AnalyticsController;
use LaravelPlus\VersionPlatformManager\Http\Controllers\DashboardController;

Route::middleware(['web', 'auth'])->group(function () {
    // Mark version as seen
    Route::post('/version-platform-manager/mark-seen', [VersionController::class, 'markSeen'])
        ->name('version-platform-manager.mark-seen');

    // Version Manager Admin routes
    Route::prefix(config('version-platform-manager.admin.route_prefix', 'admin/version-manager'))
        ->middleware(config('version-platform-manager.admin.middleware', ['web', 'auth']))
        ->group(function () {
            // Dashboard
            Route::get('/', [DashboardController::class, 'index'])->name(config('version-platform-manager.admin.route_name_prefix', 'version-manager') . '.dashboard');

            // Versions
            Route::prefix('versions')->group(function () {
                Route::get('/', [VersionController::class, 'index'])->name(config('version-platform-manager.admin.route_name_prefix', 'version-manager') . '.versions.index');
                Route::get('/create', [VersionController::class, 'create'])->name(config('version-platform-manager.admin.route_name_prefix', 'version-manager') . '.versions.create');
                Route::post('/', [VersionController::class, 'store'])->name(config('version-platform-manager.admin.route_name_prefix', 'version-manager') . '.versions.store');
                Route::get('/{version}/edit', [VersionController::class, 'edit'])->name(config('version-platform-manager.admin.route_name_prefix', 'version-manager') . '.versions.edit');
                Route::put('/{version}', [VersionController::class, 'update'])->name(config('version-platform-manager.admin.route_name_prefix', 'version-manager') . '.versions.update');
                Route::delete('/{version}', [VersionController::class, 'destroy'])->name(config('version-platform-manager.admin.route_name_prefix', 'version-manager') . '.versions.destroy');
            });

            // Users
            Route::prefix('users')->group(function () {
                Route::get('/', [UserController::class, 'index'])->name(config('version-platform-manager.admin.route_name_prefix', 'version-manager') . '.users.index');
                Route::get('/create', [UserController::class, 'create'])->name(config('version-platform-manager.admin.route_name_prefix', 'version-manager') . '.users.create');
                Route::post('/', [UserController::class, 'store'])->name(config('version-platform-manager.admin.route_name_prefix', 'version-manager') . '.users.store');
                Route::get('/{user}/edit', [UserController::class, 'edit'])->name(config('version-platform-manager.admin.route_name_prefix', 'version-manager') . '.users.edit');
                Route::put('/{user}', [UserController::class, 'update'])->name(config('version-platform-manager.admin.route_name_prefix', 'version-manager') . '.users.update');
                Route::delete('/{user}', [UserController::class, 'destroy'])->name(config('version-platform-manager.admin.route_name_prefix', 'version-manager') . '.users.destroy');
            });

            // Analytics
            Route::prefix('analytics')->group(function () {
                Route::get('/', [AnalyticsController::class, 'index'])->name(config('version-platform-manager.admin.route_name_prefix', 'version-manager') . '.analytics.index');
                Route::get('/export', [AnalyticsController::class, 'export'])->name(config('version-platform-manager.admin.route_name_prefix', 'version-manager') . '.analytics.export');
            });

            // What's New
            Route::prefix('whats-new')->group(function () {
                Route::get('/', [WhatsNewController::class, 'index'])->name(config('version-platform-manager.admin.route_name_prefix', 'version-manager') . '.whats-new.index');
                Route::get('/create', [WhatsNewController::class, 'create'])->name(config('version-platform-manager.admin.route_name_prefix', 'version-manager') . '.whats-new.create');
                Route::post('/', [WhatsNewController::class, 'store'])->name(config('version-platform-manager.admin.route_name_prefix', 'version-manager') . '.whats-new.store');
                Route::get('/{whatsNew}/edit', [WhatsNewController::class, 'edit'])->name(config('version-platform-manager.admin.route_name_prefix', 'version-manager') . '.whats-new.edit');
                Route::put('/{whatsNew}', [WhatsNewController::class, 'update'])->name(config('version-platform-manager.admin.route_name_prefix', 'version-manager') . '.whats-new.update');
                Route::delete('/{whatsNew}', [WhatsNewController::class, 'destroy'])->name(config('version-platform-manager.admin.route_name_prefix', 'version-manager') . '.whats-new.destroy');
            });
        });
}); 