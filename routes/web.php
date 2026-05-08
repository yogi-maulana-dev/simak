<?php

use App\Http\Controllers\Auth\TwoFactorChallengeController;
use App\Http\Controllers\FilePreviewController;
use App\Http\Controllers\FolderViewController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShareController;
use App\Http\Controllers\TwoFactorController;
use Illuminate\Support\Facades\Route;
use App\Livewire\Explorer;
use App\Livewire\FolderPermissionManager;
use App\Livewire\UserManager;
use App\Livewire\ActivityLogViewer;
use App\Livewire\Admin\NewsManager as AdminNewsManager;
use App\Livewire\Admin\SliderManager as AdminSliderManager;
use App\Livewire\Admin\ArsipLinkManager;

// ── Halaman Utama ──────────────────────────────────────────────────────────
Route::get('/', [HomeController::class, 'index'])->name('home');

// ── Berita Publik ──────────────────────────────────────────────────────────
Route::get('/berita', [NewsController::class, 'index'])->name('news.index');
Route::get('/berita/{slug}', [NewsController::class, 'show'])->name('news.show');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// ── 2FA Challenge (publik tapi pakai session token) ──
Route::middleware('guest')->group(function () {
    Route::get('/two-factor-challenge',  [TwoFactorChallengeController::class, 'show'])->name('two-factor.challenge');
    Route::post('/two-factor-challenge', [TwoFactorChallengeController::class, 'verify'])->name('two-factor.challenge.verify');
    Route::get('/two-factor-challenge/cancel', [TwoFactorChallengeController::class, 'cancel'])->name('two-factor.challenge.cancel');
});

Route::middleware('auth')->group(function () {
    // Preview file
    Route::get('/file/{id}/view',   [FilePreviewController::class, 'view'])->name('file.view');
    Route::get('/file/{id}/stream', [FilePreviewController::class, 'stream'])->name('file.stream');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ── 2FA setup ──
    Route::get('/profile/two-factor',                    [TwoFactorController::class, 'show'])->name('two-factor.show');
    Route::post('/profile/two-factor/confirm',           [TwoFactorController::class, 'confirm'])->name('two-factor.confirm');
    Route::post('/profile/two-factor/disable',           [TwoFactorController::class, 'disable'])->name('two-factor.disable');
    Route::post('/profile/two-factor/regenerate-codes',  [TwoFactorController::class, 'regenerateCodes'])->name('two-factor.regenerate-codes');

    // ── Explorer & Livewire ──
    Route::get('/explorer',      Explorer::class)->name('explorer');
    Route::get('/folder-manager', FolderPermissionManager::class)->name('folder-manager');

    // ── Admin only ──
    Route::middleware('can:super_admin')->group(function () {
        Route::get('/admin/folder-permissions', FolderPermissionManager::class)->name('admin.folder-permissions');
        Route::get('/admin/users',              UserManager::class)->name('admin.users');
        Route::get('/admin/activity-logs',      ActivityLogViewer::class)->name('admin.activity-logs');

        // ── CRUD Berita, Slider & Link Arsip ──
        Route::get('/admin/berita',  AdminNewsManager::class)->name('admin.news');
        Route::get('/admin/slider',  AdminSliderManager::class)->name('admin.sliders');
        Route::get('/admin/arsip',   ArsipLinkManager::class)->name('admin.arsip');
    });
});

// ── Publik — tanpa auth ──
Route::get('/arsip/{uuid}',                [FolderViewController::class, 'show'])->name('arsip.show');
Route::get('/arsip/{uuid}/preview/{fileId}', [FolderViewController::class, 'preview'])->name('arsip.preview');
Route::get('/share/{token}',               [ShareController::class, 'show'])->name('share.show');
Route::get('/share/{token}/view/{fileId}', [ShareController::class, 'viewFile'])->name('share.view');
Route::get('/share/{token}/stream/{fileId}', [ShareController::class, 'streamFile'])->name('share.stream');
Route::get('/share/{token}/download/{fileId}', [ShareController::class, 'download'])->name('share.download');

require __DIR__.'/auth.php';