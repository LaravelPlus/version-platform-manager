<?php

use Illuminate\Support\Facades\Route;
use LaravelPlus\VersionPlatformManager\Http\Controllers\VersionController;
use LaravelPlus\VersionPlatformManager\Http\Controllers\WhatsNewController;
use LaravelPlus\VersionPlatformManager\Http\Controllers\UserController;
use LaravelPlus\VersionPlatformManager\Http\Controllers\AnalyticsController;
use LaravelPlus\VersionPlatformManager\Http\Controllers\DashboardController;
use LaravelPlus\VersionPlatformManager\Http\Controllers\PublicWhatsNewController;

Route::middleware(['web', 'auth'])->group(function () {
    // Mark version as seen
    Route::post('/version-platform-manager/mark-seen', [VersionController::class, 'markSeen'])
        ->name('version-platform-manager.mark-seen');

    // Version Manager Admin routes
    Route::prefix(config('version-platform-manager.admin.route_prefix', 'admin/version-manager'))
        ->middleware(['web', 'auth'])
        ->group(function () {
            // Dashboard
            Route::get('/', [DashboardController::class, 'index'])->name(config('version-platform-manager.admin.route_name_prefix', 'version-manager') . '.dashboard');
            Route::get('/health', [DashboardController::class, 'healthCheck'])->name(config('version-platform-manager.admin.route_name_prefix', 'version-manager') . '.health');

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
                Route::get('/export-markdown/{platformVersion}', [WhatsNewController::class, 'exportMarkdown'])->name(config('version-platform-manager.admin.route_name_prefix', 'version-manager') . '.whats-new.export-markdown');
                Route::post('/import-markdown/{platformVersion}', [WhatsNewController::class, 'importMarkdown'])->name(config('version-platform-manager.admin.route_name_prefix', 'version-manager') . '.whats-new.import-markdown');
            });
        });
});

// Standalone public What's New page (requires authentication)
Route::middleware(['web', 'auth'])->group(function () {
    Route::get(config('version-platform-manager.public_whats_new.url', 'whats-new'), [PublicWhatsNewController::class, 'index'])
        ->name('version-platform-manager.whats-new.public');

    // Mark as read route for public whats-new page
    Route::post('/whats-new/mark-read', [PublicWhatsNewController::class, 'markAsRead'])
        ->name('version-platform-manager.whats-new.mark-read');
}); 