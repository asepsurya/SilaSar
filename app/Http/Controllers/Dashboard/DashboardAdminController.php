<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Akun;
use App\Models\Mitra;
use App\Models\Keuangan;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Spatie\Activitylog\Models\Activity;

class DashboardAdminController extends Controller
{

 
    public function index()
    {
        $transaksi = Transaksi::where('auth', auth()->user()->id)->get();

        $totalTransaksiluar = Transaksi::where(['auth'=> auth()->user()->id,'status_bayar'=>'Belum Bayar'])->sum('total');
        $totalTransaksi = Transaksi::where(['auth'=> auth()->user()->id,'status_bayar'=>'Sudah Bayar'])->sum('total');
// Transaksi per bulan
$transaksiPerBulan = DB::table('transaksis')
    ->select(DB::raw("DATE_FORMAT(tanggal_transaksi, '%M') as bulan"), DB::raw('COUNT(*) as total'))
    ->where('auth', auth()->user()->id) // tambahkan ini
    ->groupBy('bulan')
    ->pluck('total', 'bulan');

// Status pembayaran
$statusBayar = DB::table('transaksis')
    ->select('status_bayar', DB::raw('COUNT(*) as total'))
    ->where('auth', auth()->user()->id) // tambahkan ini
    ->groupBy('status_bayar')
    ->pluck('total', 'status_bayar');

// Transaksi per mitra
$mitra = DB::table('transaksis')
    ->join('mitras', 'transaksis.kode_mitra', '=', 'mitras.kode_mitra')
    ->select('mitras.nama_mitra', DB::raw('COUNT(*) as total'))
    ->where('transaksis.auth', auth()->user()->id) // tambahkan ini
    ->groupBy('mitras.nama_mitra')
    ->pluck('total', 'mitras.nama_mitra');

// Keuntungan
$keuntungan = DB::table('transaksis')
    ->select(
        DB::raw("DATE_FORMAT(tanggal_transaksi, '%M') as bulan"),
        DB::raw('SUM(total) as total_untung')
    )
    ->where('auth', auth()->user()->id) // tambahkan ini
    ->where('status_bayar', 'Sudah Bayar')
    ->groupBy('bulan')
    ->pluck('total_untung', 'bulan');

// Kerugian
$kerugian = DB::table('transaksis')
    ->select(DB::raw("DATE_FORMAT(tanggal_transaksi, '%M') as bulan"), DB::raw('SUM(diskon) as total_rugi'))
    ->where('auth', auth()->user()->id) // tambahkan ini
    ->groupBy('bulan')
    ->pluck('total_rugi', 'bulan');

        $logs = Activity::where(['causer_id'=>auth()->user()->id, 'log_name' => 'ikm'])->latest()->take(10)->get();
        return view('dashboard.admin',[
            'activeMenu' => 'dashboard',
            'active' => 'dashboard',
        ],compact('logs','totalTransaksiluar','totalTransaksi','transaksi',  'transaksiPerBulan',
        'statusBayar',
        'mitra',
        'keuntungan',
        'kerugian'));
    }
    public function dashboardKeuangan()
    {
        $bulanLabels = [];
        $pemasukanPerBulan = [];
        $pengeluaranPerBulan = [];

        // Ambil data pemasukan & pengeluaran per bulan berdasarkan field total dan tipe, hanya untuk user saat ini
        $keuangan = Keuangan::select(DB::raw('MONTH(STR_TO_DATE(tanggal, "%d/%m/%Y")) as bulan'), 'tipe', DB::raw('SUM(total) as total'))
            ->where('auth', auth()->user()->id)
            ->groupBy(DB::raw('MONTH(STR_TO_DATE(tanggal, "%d/%m/%Y"))'), 'tipe')
            ->get();

        // Nama bulan dalam bahasa Indonesia
        $namaBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        // Siapkan array pemasukan & pengeluaran per bulan (Januari-Desember)
        for ($i = 1; $i <= 12; $i++) {
            $bulanLabels[] = $namaBulan[$i];
            $pemasukan = $keuangan->where('bulan', $i)->where('tipe', 'pemasukan')->first();
            $pengeluaran = $keuangan->where('bulan', $i)->where('tipe', 'pengeluaran')->first();
            $pemasukanPerBulan[] = $pemasukan ? (float) $pemasukan->total : 0;
            $pengeluaranPerBulan[] = $pengeluaran ? (float) $pengeluaran->total : 0;
        }

        $akun  = Akun::all();
        $transaksi = Keuangan::with(['akun','rekening'])->where('auth', auth()->user()->id)->get();
        $logs = Activity::where(['causer_id'=>auth()->user()->id, 'log_name' => 'ikm'])->latest()->take(10)->get();
        return view('dashboard.keuangan', array_merge([
            'activeMenu' => 'dashboard',
            'active' => 'dahboardkeuangan',
        ], compact('logs','transaksi','akun','bulanLabels','pemasukanPerBulan','pengeluaranPerBulan')));


    }
    public function peta_pemasaran()
    {
        $mitras = Mitra::select('latitude', 'longitude', 'nama_mitra')
            ->where('auth', auth()->user()->id)
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->where('latitude', '!=', '')
            ->where('longitude', '!=', '')
            ->get()
            ->map(function($item) {
                return [
                    'lat' => (float) $item->latitude,
                    'lng' => (float) $item->longitude,
                    'label' => $item->nama_mitra,
                ];
            });

      $jumlahPerKota = Mitra::select('id_kota', DB::raw('count(*) as total'))
        ->where('auth',auth()->user()->id)
        ->whereNotNull('id_kota')
        ->groupBy('id_kota')
        ->get();


        $logs = Activity::where(['causer_id'=>auth()->user()->id, 'log_name' => 'ikm'])->latest()->take(10)->get();
        return view('dashboard.peta',[
            'activeMenu' => 'dashboard',
            'active' => 'peta',
        ],compact('logs','mitras','jumlahPerKota'));
    }
}
