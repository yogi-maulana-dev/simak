<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Models\Prodi;
use App\Livewire\Arsip\Index as ArsipIndex;
use App\Livewire\Arsip\Create as ArsipCreate;
use App\Livewire\Arsip\Edit as ArsipEdit;
use App\Livewire\Arsip\Show as ArsipShow;
use App\Livewire\Arsip\Delete as ArsipDelete;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

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

    // Arsip Routes
    Route::prefix('arsip')->name('arsip.')->group(function () {
        Route::get('/', ArsipIndex::class)->name('index');
        Route::get('/create', ArsipCreate::class)->name('create');
    
        Route::get('/{arsip}/edit', ArsipEdit::class)->name('edit');
    
    });
});

require __DIR__.'/auth.php';