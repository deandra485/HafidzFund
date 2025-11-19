<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Ustadz\Dashboard as UstadzDashboard;
use App\Livewire\Admin\DataSantri;
use App\Livewire\Admin\DataUstadz;
use App\Livewire\Admin\ManajemenKeuangan;
use App\Livewire\Ustadz\InputSetoran;
use App\Livewire\Admin\TransaksiForm;
use App\Livewire\Admin\LaporanHafalan;
use App\Livewire\Admin\SantriExcel;
use App\Livewire\Ustadz\SantriBinaan;
use App\Livewire\Ustadz\KehadiranSantri;
use App\Livewire\Ustadz\RiwayatHafalan;
use App\Livewire\Ustadz\MonitoringHafalan;
use App\Livewire\Admin\Profile;
use App\Livewire\Ustadz\ProfileUstadz;
use App\Livewire\Admin\Notifikasi;

// ==============================
// 🏠 HALAMAN UTAMA
// ==============================
Route::get('/', function () {
    return view('welcome');
})->name('home');


// ==============================
// 🔐 AUTH ROUTES (prefix: /auth)
// ==============================
Route::prefix('auth')->name('auth.')->group(function () {
    Route::get('/login', Login::class)->name('login');
    Route::get('/register', Register::class)->name('register');

    Route::get('/logout', function () {
        // dummy logout tanpa auth
        return redirect()->route('auth.login');
    })->name('logout');

});


// ==============================
// 👑 ADMIN ROUTES (prefix: /admin)
// ==============================
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', AdminDashboard::class)->name('dashboard');
    Route::get('/santri', DataSantri::class)->name('data-santri');
    Route::get('/keuangan', ManajemenKeuangan::class)->name('manajemen-keuangan');
    Route::get('/admin/transaksi/create', TransaksiForm::class)->name('transaksi.create');
    Route::get('/admin/transaksi/edit/{id}', TransaksiForm::class)->name('transaksi.edit');
    Route::get('/admin/dataustadz', DataUstadz::class)->name('data-ustadz');
    Route::get('/laporan/hafalan', LaporanHafalan::class)->name('laporan.hafalan');
    Route::get('/santri/excel', SantriExcel::class)->name('santri-excel');
    Route::get('/profile', Profile::class)->name('profile');
    Route::get('/notifikasi', Notifikasi::class)->name('notifikasi');
});


// ==============================
// 🕌 USTADZ ROUTES (prefix: /ustadz)
// ==============================
// Sekarang route /ustadz/dashboard langsung menuju InputSetoran
Route::prefix('ustadz')->name('ustadz.')->group(function () {
    Route::get('/dashboard', UstadzDashboard::class)->name('dashboard'); // ✅ langsung ke InputSetoran
    Route::get('/setoran', InputSetoran::class)->name('setoran');
    Route::get('/santri-binaan', SantriBinaan::class)->name('santri-binaan');
    Route::get('/input-setoran/{santri_id?}', InputSetoran::class)->name('input-setoran');
    Route::get('/kehadiran-santri', KehadiranSantri::class)->name('kehadiran-santri');
    Route::get('/riwayat-hafalan', RiwayatHafalan::class)->name('riwayat-hafalan');
    Route::get('/monitoring-hafalan', MonitoringHafalan::class)->name('monitoring-hafalan');
    Route::get('/profile', ProfileUstadz::class)->name('profile-ustadz');
});
