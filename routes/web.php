<?php

use Carbon\Carbon;
use App\Models\User;
use App\Models\Prodi;
use App\Mail\PasswordResetTokenMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\TokenResetPasswordController;

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
        // User Management
        Route::get('/users', \App\Livewire\Admin\UserIndex::class)->name('users.index');
        Route::get('/users/create', \App\Livewire\Admin\UserCreate::class)->name('users.create');
        Route::get('/users/{user}/edit', \App\Livewire\Admin\UserEdit::class)->name('users.edit');
        Route::get('/users/{user}/reset', \App\Livewire\Admin\ResetPassword::class)->name('users.reset');
        Route::get('/users/{user}/reset-password', \App\Livewire\Admin\ResetPassword::class)->name('users.reset-password');
        
        // Arsip Management
        Route::get('/arsip', \App\Livewire\AdminArsip\Index::class)->name('arsip.index');
        Route::get('/arsip/create', \App\Livewire\AdminArsip\Create::class)->name('arsip.create');
        Route::get('/arsip/{arsip}/edit', \App\Livewire\AdminArsip\Edit::class)->name('arsip.edit');

        // Token Reset Password (ini dipindahkan ke dalam group admin)
        Route::get('/reset-password/token', [TokenResetPasswordController::class, 'showResetForm'])
            ->name('password.reset.token');
    });
});

Route::get('/test-email', function () {
    $user = User::first();
    
    if (!$user) {
        return 'No user found';
    }
    
    $token = '123456';
    $expiresAt = Carbon::now()->addHours(1);
    
    Mail::to($user->email)->send(new PasswordResetTokenMail($user, $token, $expiresAt));
    
    return 'Email sent to ' . $user->email;
});

require __DIR__.'/auth.php'; 
