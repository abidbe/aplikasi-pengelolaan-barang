<?php

use App\Http\Controllers\BarangMasukController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SubKategoriController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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

// Redirect root to login
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Barang Masuk Routes (all authenticated users)
    Route::resource('barang-masuks', BarangMasukController::class);
    Route::post('barang-masuks/{barangMasuk}/toggle-verification', [BarangMasukController::class, 'toggleVerification'])->name('barang-masuks.toggle-verification');
    Route::get('barang-masuks/{barangMasuk}/print', [BarangMasukController::class, 'print'])->name('barang-masuks.print');
    Route::get('barang-masuks-export', [BarangMasukController::class, 'export'])->name('barang-masuks.export');

    // API Routes for dynamic data
    Route::get('api/sub-kategoris', [BarangMasukController::class, 'getSubKategoris'])->name('api.sub-kategoris');
    Route::get('api/batas-harga', [BarangMasukController::class, 'getBatasHarga'])->name('api.batas-harga');
    Route::get('api/kategoris', [SubKategoriController::class, 'getKategoris'])->name('api.kategoris');
});

// Admin only routes
Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('users', UserController::class);
    Route::post('users/{user}/toggle-lock', [UserController::class, 'toggleLock'])->name('users.toggle-lock');

    // Master Data Routes
    Route::resource('kategoris', KategoriController::class);
    Route::resource('sub-kategoris', SubKategoriController::class);
});

require __DIR__ . '/auth.php';
