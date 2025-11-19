<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\DendaController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\PengembalianController;
use App\Http\Controllers\KeluhanController;
use App\Http\Controllers\PerpanjanganController;
use App\Http\Controllers\SerahTerimaController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\QrController;
use App\Http\Controllers\RiwayatController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\PetugasController;

// Halaman umum (tanpa role)
Route::get('/', function () {
    if (auth()->check()) {
        $user = auth()->user();
        return match ($user->role) {
            'petugas'   => redirect()->route('petugas.dashboard'),
            'mahasiswa' => redirect()->route('mahasiswa.dashboard'),
            default     => redirect()->route('login'),
        };
    }
    return redirect()->route('login');
})->name('home');

// Endpoint dashboard default Breeze -> arahkan sesuai role
Route::get('/dashboard', function () {
    $user = auth()->user();

    if (!$user) {
        return redirect()->route('home');
    }

    return match ($user->role) {
        'petugas'   => redirect()->route('petugas.dashboard'),
        'mahasiswa' => redirect()->route('mahasiswa.dashboard'),
        default     => redirect()->route('home'),
    };
})->middleware('auth')->name('dashboard');

// =======================
//  Mahasiswa
// =======================
Route::middleware(['auth','role:mahasiswa'])->group(function() {
    Route::get('/mahasiswa/dashboard', [MahasiswaController::class, 'index'])->name('mahasiswa.dashboard');

    Route::resource('peminjaman', PeminjamanController::class);
    Route::resource('keluhan', KeluhanController::class)->except(['edit', 'update', 'destroy']);
    Route::resource('perpanjangan', PerpanjanganController::class);
    Route::resource('serahterima', SerahTerimaController::class)->only(['create','store']);
    Route::resource('notifikasi', NotifikasiController::class)->only(['index']);
    Route::get('/qr/{id}', [QrController::class, 'show'])->name('qr.show');
    Route::get('/riwayat', [RiwayatController::class, 'index'])->name('riwayat.index');
});

// =======================
//  Petugas
// =======================
Route::middleware(['auth','role:petugas'])->group(function() {
    Route::get('/petugas/dashboard', [PetugasController::class, 'dashboard'])->name('petugas.dashboard');

    // CRUD barang hanya untuk petugas
    // index & show kita pisahkan di bawah (bisa diakses mahasiswa juga)
    Route::resource('barang', BarangController::class)->except(['index', 'show']);

    Route::resource('kategori', KategoriController::class);
    Route::resource('service', ServiceController::class);
    Route::resource('denda', DendaController::class);
    Route::resource('pengembalian', PengembalianController::class)->only(['index']);

    Route::post('/perpanjangan/{id}/approve', [PerpanjanganController::class, 'approve'])->name('perpanjangan.approve');
    Route::post('/serahterima/{id}/approve', [SerahTerimaController::class, 'approve'])->name('serahterima.approve');
    Route::resource('serahterima', SerahTerimaController::class)->only(['index']);

    Route::resource('notifikasi', NotifikasiController::class)->only(['index']);
});

// =======================
//  Barang: bisa dilihat petugas & mahasiswa (read-only)
// =======================
// Di sini mahasiswa bisa:
// - GET /barang          -> route('barang.index')
// - GET /barang/{barang} -> route('barang.show')
// Tetapi tidak bisa create/edit/delete karena itu ada di grup petugas di atas
Route::middleware(['auth'])->group(function () {
    Route::get('/barang', [BarangController::class, 'index'])->name('barang.index');
    Route::get('/barang/{barang}', [BarangController::class, 'show'])->name('barang.show');
});

require __DIR__.'/auth.php';
