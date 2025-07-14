<?php

use Illuminate\Support\Facades\Route;
use LaravelPlus\VersionPlatformManager\Http\Controllers\VersionController;
use LaravelPlus\VersionPlatformManager\Http\Controllers\WhatsNewController;

Route::middleware(['web', 'auth'])->group(function () {
    // Mark version as seen
    Route::post('/version-platform-manager/mark-seen', [VersionController::class, 'markSeen'])
        ->name('version-platform-manager.mark-seen');

    // Admin routes
    Route::prefix(config('version-platform-manager.admin.route_prefix', 'admin/versions'))
        ->middleware(config('version-platform-manager.admin.middleware', ['web', 'auth']))
        ->group(function () {
            Route::get('/', [VersionController::class, 'index'])->name('admin.versions.index');
            Route::get('/create', [VersionController::class, 'create'])->name('admin.versions.create');
            Route::post('/', [VersionController::class, 'store'])->name('admin.versions.store');
            Route::get('/{version}/edit', [VersionController::class, 'edit'])->name('admin.versions.edit');
            Route::put('/{version}', [VersionController::class, 'update'])->name('admin.versions.update');
            Route::delete('/{version}', [VersionController::class, 'destroy'])->name('admin.versions.destroy');

            // What's New routes
            Route::get('/whats-new', [WhatsNewController::class, 'index'])->name('admin.whats-new.index');
            Route::get('/whats-new/create', [WhatsNewController::class, 'create'])->name('admin.whats-new.create');
            Route::post('/whats-new', [WhatsNewController::class, 'store'])->name('admin.whats-new.store');
            Route::get('/whats-new/{whatsNew}/edit', [WhatsNewController::class, 'edit'])->name('admin.whats-new.edit');
            Route::put('/whats-new/{whatsNew}', [WhatsNewController::class, 'update'])->name('admin.whats-new.update');
            Route::delete('/whats-new/{whatsNew}', [WhatsNewController::class, 'destroy'])->name('admin.whats-new.destroy');
        });
}); 