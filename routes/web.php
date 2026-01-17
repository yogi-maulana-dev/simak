<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Models\Prodi;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/get-prodi/{fakultas}', function ($fakultas) {
    return Prodi::where('fakultas_id', $fakultas)
        ->select('id', 'nama_prodi')
        ->get();
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Arsip Routes untuk semua role KECUALI superadmin
    // Role yang diperbolehkan: 2,3,4,5,6 (admin_univ, admin_fakultas, admin_prodi, asesor_fakultas, asesor_prodi)
    Route::prefix('arsip')->name('arsip.')->middleware(['auth', 'role:2,3,4,admin_univ,admin_fakultas,admin_prodi'])->group(function () {
        Route::get('/', \App\Livewire\Arsip\Index::class)->name('index');
        Route::get('/create', \App\Livewire\Arsip\Create::class)->name('create');
        Route::get('/{arsip}/edit', \App\Livewire\Arsip\Edit::class)->name('edit');
    });

    // Admin Arsip khusus untuk superadmin (role_id 1)
    Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:1,superadmin'])->group(function () {
        Route::get('/arsip', \App\Livewire\AdminArsip\Index::class)->name('arsip.index');
        Route::get('/arsip/create', \App\Livewire\AdminArsip\Create::class)->name('arsip.create');
        Route::get('/arsip/{arsip}/edit', \App\Livewire\AdminArsip\Edit::class)->name('arsip.edit');
    });
});

require __DIR__.'/auth.php';