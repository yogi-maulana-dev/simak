<?php

use App\Http\Controllers\FilePreviewController;
use App\Http\Controllers\FolderViewController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShareController;
use Illuminate\Support\Facades\Route;
use App\Livewire\Explorer;
use App\Livewire\FolderPermissionManager;
use App\Livewire\UserManager;
use App\Livewire\ActivityLogViewer;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Preview file — hanya bisa lihat, tidak download
    Route::get('/file/{id}/view', [FilePreviewController::class, 'view'])->name('file.view');
    Route::get('/file/{id}/stream', [FilePreviewController::class, 'stream'])->name('file.stream');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
      Route::get('/explorer', Explorer::class)->name('explorer');
      Route::get('/folder-manager', FolderPermissionManager::class)->name('folder-manager');
        // Hanya super_admin — guard juga ada di mount() component
    Route::get('/admin/folder-permissions', FolderPermissionManager::class)
         ->name('admin.folder-permissions')
         ->middleware('can:super_admin');

    Route::get('/admin/users', UserManager::class)
         ->name('admin.users')
         ->middleware('can:super_admin');

    Route::get('/admin/activity-logs', ActivityLogViewer::class)
         ->name('admin.activity-logs')
         ->middleware('can:super_admin');
});

// Publik — tanpa auth
Route::get('/arsip/{uuid}', [FolderViewController::class, 'show'])->name('arsip.show');
Route::get('/arsip/{uuid}/preview/{fileId}', [FolderViewController::class, 'preview'])->name('arsip.preview');
Route::get('/share/{token}', [ShareController::class, 'show'])->name('share.show');
Route::get('/share/{token}/view/{fileId}', [ShareController::class, 'viewFile'])->name('share.view');
Route::get('/share/{token}/stream/{fileId}', [ShareController::class, 'streamFile'])->name('share.stream');
Route::get('/share/{token}/download/{fileId}', [ShareController::class, 'download'])->name('share.download');

require __DIR__.'/auth.php';
