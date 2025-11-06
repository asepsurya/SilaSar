<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UpdateController;
use App\Http\Controllers\Ikm\IkmController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Nota\NotaController;
use App\Http\Controllers\Auth\logoutController;
use App\Http\Controllers\Mitra\MitraController;
use App\Http\Controllers\Auth\registerController;
use App\Http\Controllers\Produk\ProdukController;
use App\Http\Controllers\Region\RegionController;
use App\Http\Controllers\Laporan\LaporanController;
use App\Http\Controllers\Keuangan\HistoryController;
use App\Http\Controllers\Keuangan\KeuanganController;
use App\Http\Controllers\Transaksi\TransaksiController;
use App\Http\Controllers\Perusahaan\PerusahaanController;
use App\Http\Controllers\Dashboard\DashboardAdminController;


Route::get('/', [AuthController::class, 'index'])->middleware('guest');
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');
Route::get('/reset-password', [AuthController::class, 'passReset'])->name('passReset')->middleware('guest');
Route::post('/reset-password/action', [AuthController::class, 'passResetAction'])->name('passResetAction')->middleware('guest');
Route::get('/refresh-captcha', [AuthController::class, 'refreshCaptcha'])->name('refreshCaptcha');

Route::get('/register', [registerController::class, 'register'])->name('register')->middleware('guest');
Route::post('/mail/resend/', [registerController::class, 'resend'])->name('resend')->middleware('guest');

Route::get('/account/activate/{token}', [registerController::class, 'activate'])->name('activate')->middleware('guest');
Route::get('/register/success/{token}', [registerController::class, 'successRegister'])->name('successRegister');

Route::post('/check-email', [registerController::class, 'checkEmail'])->name('check.email');
Route::post('/register/auth', [registerController::class, 'registerAction'])->name('register.add')->middleware('guest');
Route::post('/logout', [logoutController::class, 'logout'])->name('logout');


// ------------------------------------------------
// route Regency Administrasi
// ------------------------------------------------
Route::post('/getkabupaten',[RegionController::class,'getkabupaten'])->name('getkabupaten');
Route::post('/getkecamatan',[RegionController::class,'getkecamatan'])->name('getkecamatan');
Route::post('/getdesa',[RegionController::class,'getdesa'])->name('getdesa');

Route::middleware(['auth','checkPerusahaan','redirectIfNotAdmin'])->group(function () {
    Route::post('/change-password', [AuthController::class, 'passChange'])->name('passChange')->middleware('auth');
    // ------------------------------------------------
    // Middleware untuk superadmin|admin|pengguna level platinum
    // ------------------------------------------------
    Route::middleware(['role:superadmin|admin|platinum'])->group(function () {
        // ------------------------------------------------
        // Dashboard Admin
        // ------------------------------------------------
        Route::get('/dashboard', [DashboardAdminController::class, 'index'])->name('dashboard');
        Route::get('/dashboard/peta pemasaran', [DashboardAdminController::class, 'peta_pemasaran'])->name('dashboard.peta');
        // ------------------------------------------------
        // Route Mitra
        // ------------------------------------------------
        Route::get('/mitra', [MitraController::class, 'index'])->name('index.mitra');
        Route::get('/mitra/detail/{id}', [MitraController::class, 'mitraDetail'])->name('detail.mitra');
        Route::post('/mitra/detail/update', [MitraController::class, 'mitraupdate'])->name('update.mitra');
        Route::delete('/mitra/produk/delete/{id}', [MitraController::class, 'mitaProdukDelete'])->name('produk.mitra.delete');
        Route::get('/mitra/create', [MitraController::class, 'create'])->name('mitra.add');
        Route::get('/mitra/delete/{id}', [MitraController::class, 'mitraDelete'])->name('mitra.delete');
        Route::post('/mitra/create/action', [MitraController::class, 'createAction'])->name('mitra.create');
        Route::post('/resolve-maps-link', [MitraController::class, 'resolve']);
        // ------------------------------------------------
        // Route Produk
        // ------------------------------------------------
        Route::get('/produk', [ProdukController::class, 'index'])->name('index.produk');
        Route::get('/produk/create', [ProdukController::class, 'create'])->name('index.create.produk');
        Route::get('/produk/update/{id}', [ProdukController::class, 'update'])->name('index.update.produk');
        Route::post('/produk/update', [ProdukController::class, 'updateaction'])->name('action.update');
        Route::get('/produk/delete/{id}', [ProdukController::class, 'deleteaction'])->name('action.delete');
        Route::post('/produk/store', [ProdukController::class, 'store'])->name('produk.store');
        Route::get('/produk/category', [ProdukController::class, 'category'])->name('produk.category');
        Route::post('/produk/category', [ProdukController::class, 'createCategory'])->name('category.add');
        Route::post('/produk/category/update', [ProdukController::class, 'updateCategory'])->name('category.update');
        Route::get('/produk/category/delete/{id}', [ProdukController::class, 'deleteCategory'])->name('category.delete');
        Route::get('/category/list', [ProdukController::class, 'list'])->name('category.list');
        Route::get('/satuan', [ProdukController::class, 'satuan'])->name('satuan.index');
        Route::post('/satuan/add', [ProdukController::class, 'satuanAdd'])->name('satuan.add');
        Route::post('/satuan/update', [ProdukController::class, 'satuanUpdate'])->name('satuan.update');
        Route::get('/satuan/delete/{id}', [ProdukController::class, 'satuanDelete'])->name('satuan.delete');
        Route::get('/management/stok/add', [ProdukController::class, 'manajemenStok'])->name('manajemenStok');
        Route::post('/management/stok/add', [ProdukController::class, 'manajemenStokcreate'])->name('manajemenStok.create');
        Route::get('/management/stok', [ProdukController::class, 'manajemenStokIndex'])->name('manajemenStok.index');
        Route::get('/management/stok/update/{id}', [ProdukController::class, 'manajemenStokUpdate'])->name('manajemenStok.update');
        Route::get('/management/stok/delete/{id}', [ProdukController::class, 'manajemenStokDelete'])->name('manajemenStok.delete');
        Route::get('/management/stok/transaksi/delete/{id}', [ProdukController::class, 'manajemenStokDeleteItem'])->name('manajemenStok.deleteItem');

        

        // ------------------------------------------------
        // Route  Transaksi Induk Mitra
        // ------------------------------------------------
        Route::get('/transaksi', [TransaksiController::class, 'transaksiIndex'])->name('transaksi.index');
        Route::get('/transaksi/{id}', [TransaksiController::class, 'DetailTransaki'])->name('transaksi.detail');
        Route::post('/transaksi/create', [TransaksiController::class, 'transaksiCreate'])->name('transaksi.create');
        Route::post('/transaksi/update', [TransaksiController::class, 'transaksiUpdate'])->name('transaksi.update');
        Route::get('/transaksi/cetak/konsinyasi/{id}', [TransaksiController::class, 'konsinyasi'])->name('transaksi.konsinyasi');
        Route::get('/transaksi/cetak/kwitansi/{id}', [TransaksiController::class, 'kwitansi'])->name('transaksi.kwitansi');
        Route::get('/transaksi/cetak/invoice/{id}', [TransaksiController::class, 'kwitansi'])->name('transaksi.invoce');
        Route::post('/transaksi/cetak/invoice/notes', [TransaksiController::class, 'notes'])->name('transaksi.notes');

        Route::get('/transaksi/dok/konsinyasi/{id}', [TransaksiController::class, 'konsinyasidok'])->name('transaksi.konsinyasi.dok');
        Route::post('/update-penawaran', [TransaksiController::class, 'updateKodeTransaksi'])->name('updateKodeTransaksi');
        Route::post('/hapus-produk-transaksi', [TransaksiController::class, 'hapusProduk'])->name('transaksi.hapus-produk');
        Route::get('/transaksi/destory/{id}', [TransaksiController::class, 'hapusTransksi'])->name('hapusTransksi');
        Route::post('/save-pdf', [TransaksiController::class, 'savePdf'])->name('savePdf');


        Route::get('/laporan/penjualan', [TransaksiController::class, 'laporanTransaksi'])->name('laporan.penjualan');
        Route::get('/laporan/konsinyasi/{id}', [LaporanController::class, 'laporankionsinyasi'])->name('laporan.konsinyasi');
        Route::get('/laporan/invoice/{id}', [LaporanController::class, 'laporaninvoice'])->name('laporan.invoice');
        Route::get('/laporan/kwitansi/{id}', [LaporanController::class, 'laporankwitansi'])->name('laporan.kwitansi');
        Route::get('/laporan/labarugi', [LaporanController::class, 'laporanlabarugi'])->name('laporan.labarugi');
        Route::get('/laporan/pdf/labarugi', [LaporanController::class, 'laporanlabarugipdf'])->name('laporan.labarugipdf');
        Route::get('/laporan/pdf/neraca', [LaporanController::class, 'laporanneraca'])->name('laporan.nercapdf');
        Route::get('/laporan/pdf', [TransaksiController::class, 'exportPDF'])->name('laporan.exportPDF');


        // ------------------------------------------------
        // Route Nota
        // ------------------------------------------------

        Route::get('/transaksi/nota/create/{id}', [TransaksiController::class, 'manualNota'])->name('transaksi.nota.manual');
        Route::get('/transaksi/invoice/create/{id}', [TransaksiController::class, 'manualNota'])->name('transaksi.invoice.manual');
        Route::get('/transaksi/kwitansi/create/{id}', [TransaksiController::class, 'manualNota'])->name('transaksi.kwitansi.manual');
        Route::get('/transaksi/nota/detail/{id}', [TransaksiController::class, 'manualNota'])->name('transaksi.nota.detail');

        Route::post('/transaksi/nota/add', [TransaksiController::class, 'manualadd'])->name('transaksi.nota.add');
        Route::get('/transaksi/delete/{id}', [TransaksiController::class, 'itemDelete'])->name('transaksi.item.delete');
        Route::get('/nota', [NotaController::class, 'nota'])->name('nota.index');
        Route::get('/nota/delete/{id}', [NotaController::class, 'notaDelete'])->name('nota.delete');

        Route::get('/nota2/{id}', [NotaController::class, 'nota2'])->name('nota');
        Route::get('/update', [UpdateController::class, 'index'])->name('update.index');
         Route::post('/update/run', [UpdateController::class, 'run'])->name('update.run');
    });

    Route::middleware(['role:superadmin|admin|platinum|gold'])->get('/dashboard/keuangan', [DashboardAdminController::class, 'dashboardKeuangan'])->name('dashboard.keuangan');


    // ------------------------------------------------
    // Route IKM
    // ------------------------------------------------
    Route::get('/people', [IkmController::class, 'index'])->name('index.ikm')->middleware('role:admin|superadmin');
    Route::get('/people/create', [IkmController::class, 'create'])->name('ikm.create')->middleware('role:admin|superadmin');
    Route::post('/people/create', [IkmController::class, 'store'])->name('ikm.store')->middleware('role:admin|superadmin');
    Route::get('/people/delete/{id}', [IkmController::class, 'delete'])->name('ikm.delete')->middleware('role:admin|superadmin');
    Route::get('/people/update/{id}', [IkmController::class, 'update'])->name('ikm.update');
    Route::post('/people/update/action', [IkmController::class, 'updateIkm'])->name('ikm.update.action');
    Route::post('/people/update/foto', [IkmController::class, 'updateFoto'])->name('ikm.update.foto');
    Route::post('/keaktifan', [IkmController::class, 'getAktifData'])->name('getAktifData');
    Route::get('/people/keaktifan', [IkmController::class, 'keaktifan'])->name('keaktifan.pengguna');
    Route::post('/ikm/updaterole/{id}', [IkmController::class, 'updateRole'])->name('ikm.updateRole');
    // ------------------------------------------------
    // Route Keuangan
    // ------------------------------------------------
    Route::get('/keuangan', [KeuanganController::class, 'index'])->name('index.keuangan');
    Route::get('/keuangan/kalender', [KeuanganController::class, 'kelenderIndex'])->name('keuangan.kalender');
    Route::post('/keuangan/add', [KeuanganController::class, 'keuanganAdd'])->name('keuangan.add');
    Route::post('/keuangan/update', [KeuanganController::class, 'keuanganUpdate'])->name('keuangan.update');
    Route::get('/keuangan/delete/{id}', [KeuanganController::class, 'keuanganDelete'])->name('keuangan.delete');
    Route::get('/keuangan/export/pdf', [KeuanganController::class, 'keuanganPDF'])->name('keuangan.pdf');
    Route::get('/history/cetak-pdf/{id_rekening}', [KeuanganController::class, 'cetakHistoryPDF'])->name('history.cetak-pdf');
    Route::get('/laporan/neraca', [KeuanganController::class, 'neraca'])->name('laporan.neraca');
    Route::get('/laporan/neraca/pdf', [KeuanganController::class, 'neracapdf'])->name('laporan.neracaPdf');
    Route::get('/laporan/neracasaldo', [KeuanganController::class, 'neracaSaldo'])->name('laporan.neraca_saldo');
    Route::get('/laporan/labarugi', [KeuanganController::class, 'labarugi'])->name('laporan.labarugi');
    Route::get('/laporan/transaksi', [KeuanganController::class, 'laptransaksi'])->name('laporan.transaksi');

    // ------------------------------------------------
    // Route Akun dan Rekening
    // ------------------------------------------------
    Route::get('/akun', [KeuanganController::class, 'IndexAkun'])->name('index.akun');
    Route::post('/akun/create', [KeuanganController::class, 'akunCreate'])->name('akun.create');
    Route::post('/akun/update', [KeuanganController::class, 'akunUpdate'])->name('akun.update');
    Route::get('/akun/delete/{id}', [KeuanganController::class, 'akunDelete'])->name('akun.delete');
    Route::get('/rekening', [KeuanganController::class, 'rekeningIndex'])->name('akun.rekening');
    Route::post('/rekening', [KeuanganController::class, 'rekeningAdd'])->name('rekening.add');
    Route::post('/rekening/update', [KeuanganController::class, 'rekeningUpdate'])->name('rekening.update');
    Route::delete('/rekening/hapus/{id}', [KeuanganController::class, 'rekeningDelete'])->name('rekening.delete');
    Route::get('/rekening/default/{id}', [KeuanganController::class, 'rekeningDefault'])->name('default.rekening');
    Route::get('/rekening/{id_rekening}', [HistoryController::class, 'rekeningHistory'])->name('rekening.history');
    // ------------------------------------------------
    // Route Perusahaan
    // ------------------------------------------------
    Route::get('/create/perusahaan/auth', [PerusahaanController::class, 'index'])->middleware('check.auth.perusahaan')->name('perusahaan.index');
    Route::post('/create/perusahaan', [PerusahaanController::class, 'create'])->name('perusahaan.create');
    Route::get('/setelan', [PerusahaanController::class, 'PerusahaanSetting'])->name('perusahaan.setting');

    Route::post('/perusahaan/upload-logo', [PerusahaanController::class, 'uploadLogo'])->name('perusahaan.update.logo');
    Route::post('/perusahaan/update-profil', [PerusahaanController::class, 'updateProfil'])->name('perusahaan.update.profil');
    Route::post('/perusahaan/update-legalitas', [PerusahaanController::class, 'updateLegalitas'])->name('perusahaan.update.legalitas');
    Route::get('/perusahaan/hapus-legalitas/{id}', [PerusahaanController::class, 'HapusLegalitas'])->name('perusahaan.hapus.legalitas');
    Route::post('/perusahaan/update-stamp', [PerusahaanController::class, 'updateStamp'])->name('perusahaan.update.stamp');

    // ------------------------------------------------
    // Route Exsport PDF
    // ------------------------------------------------

});



