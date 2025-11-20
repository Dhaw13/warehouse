<?php
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\VerifikasiController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\VerifikasiBarangController;
use App\Http\Controllers\LaporanPenerimaanController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {
   
  

 Route::resource('po', PurchaseOrderController::class); 
 Route::get('/verifikasi', [VerifikasiBarangController::class, 'index'])->name('verifikasi.index');
 Route::get('/verifikasi/{po}', [VerifikasiBarangController::class, 'show'])->name('verifikasi.show');
 Route::post('/verifikasi/{po}', [VerifikasiBarangController::class, 'store'])->name('verifikasi.store');

 Route::resource('laporan', LaporanPenerimaanController::class);
   
 Route::resource('verifikasi', VerifikasiBarangController::class);

 Route::resource('pbarang', BarangController::class);

 Route::resource('permissions', PermissionController::class);
    
 Route::resource('roles', RoleController::class);
    
 Route::resource('users', UserController::class);

});




require __DIR__.'/auth.php';
