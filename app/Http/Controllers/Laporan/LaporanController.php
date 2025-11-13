<?php

namespace App\Http\Controllers\Laporan;

use App\Models\Akun;
use App\Models\AkunTable;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use App\Models\KeuanganTable;
use App\Models\KeuanganTableku;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;

class LaporanController extends Controller
{
    public function laporankionsinyasi($id){
        $transaksi = Transaksi::with(['mitra', 'ProdukTransaksi.produk', 'ProdukTransaksi.penawaran'])
            ->where('kode_transaksi', $id)
            ->firstOrFail();

        $perusahaan = auth()->user()->perusahaanUser;

        // Handle logo (base64 supaya aman di Dompdf)
        $logo = null;
        if ($perusahaan->logo && file_exists(storage_path('app/public/' . $perusahaan->logo))) {
            $logoPath = storage_path('app/public/' . $perusahaan->logo);
            $logo = 'data:image/' . pathinfo($logoPath, PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents($logoPath));
        } else {
            // fallback ke logo default di public/assets
            $defaultPath = public_path('assets/default_logo.png');
            if (file_exists($defaultPath)) {
                $logo = 'data:image/png;base64,' . base64_encode(file_get_contents($defaultPath));
            }
        }


        // Alamat perusahaan
        $alamat = $perusahaan->alamat;

        // Generate PDF
        $pdf = Pdf::loadView('report.konsinyasi', [
            'transaksi'   => $transaksi,
            'perusahaan'  => $perusahaan,
            'logo'        => $perusahaan->logo,
            'alamat'      => $alamat,
        ])->setPaper('A4', 'portrait');

        return $pdf->stream("Nota-Konsinyasi-{$transaksi->kode_transaksi}.pdf");

    }
    public function laporaninvoice($id){
        $transaksi = Transaksi::with(['mitra', 'ProdukTransaksi.produk', 'ProdukTransaksi.penawaran'])
            ->where('kode_transaksi', $id)
            ->firstOrFail();

        $perusahaan = auth()->user()->perusahaanUser;

        // Handle logo (base64 supaya aman di Dompdf)
        $logo = null;
        if ($perusahaan->logo && file_exists(storage_path('app/public/' . $perusahaan->logo))) {
            $logoPath = storage_path('app/public/' . $perusahaan->logo);
            $logo = 'data:image/' . pathinfo($logoPath, PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents($logoPath));
        } else {
            // fallback ke logo default di public/assets
            $defaultPath = public_path('assets/default_logo.png');
            if (file_exists($defaultPath)) {
                $logo = 'data:image/png;base64,' . base64_encode(file_get_contents($defaultPath));
            }
        }


        // Alamat perusahaan
        $alamat = $perusahaan->alamat;

        // Generate PDF
        $pdf = Pdf::loadView('report.invoice', [
            'transaksi'   => $transaksi,
            'perusahaan'  => $perusahaan,
            'logo'        => $perusahaan->logo,
            'alamat'      => $alamat,
        ])->setPaper('A4', 'portrait');

        return $pdf->stream("INVOICE-{$transaksi->kode_transaksi}.pdf");
    }
    public function laporankwitansi($id){
        $transaksi = Transaksi::with(['mitra', 'ProdukTransaksi.produk', 'ProdukTransaksi.penawaran'])
            ->where('kode_transaksi', $id)
            ->firstOrFail();

        $perusahaan = auth()->user()->perusahaanUser;

        // Handle logo (base64 supaya aman di Dompdf)
        $defaulttd = public_path('assets/ttd-default.png');
        $defaultStamp = public_path('assets/stamp-default.png');

        $ttdPath = $defaulttd;
        if ($perusahaan->ttd && file_exists(storage_path('app/public/' . $perusahaan->ttd))) {
            $ttdPath = storage_path('app/public/' . $perusahaan->ttd);
        }

        $stempelPath = $defaultStamp;
        if ($perusahaan->stempel && file_exists(storage_path('app/public/' . $perusahaan->stempel))) {
            $stempelPath = storage_path('app/public/' . $perusahaan->stempel);
        }

        $logo = null;
        if ($perusahaan->logo && file_exists(storage_path('app/public/' . $perusahaan->logo))) {
            $logoPath = storage_path('app/public/' . $perusahaan->logo);
            $logo = 'data:image/' . pathinfo($logoPath, PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents($logoPath));
        } else {
            // fallback ke logo default di public/assets
            $defaultPath = public_path('assets/default_logo.png');
            if (file_exists($defaultPath)) {
                $logo = 'data:image/png;base64,' . base64_encode(file_get_contents($defaultPath));
            }
        }
        // Alamat perusahaan
        $alamat = $perusahaan->alamat;
        // Generate PDF
        $pdf = Pdf::loadView('report.kwitansi', [
            'transaksi'   => $transaksi,
            'perusahaan'  => $perusahaan,
            'logo'        => $perusahaan->logo,
            'alamat'      => $alamat,
            'ttd'         => $ttdPath,
            'stempel'      => $stempelPath,
        ])->setPaper('A4', 'portrait');

        return $pdf->stream("NOTA-PEMBAYARAN-{$transaksi->kode_transaksi}.pdf");

        }
    public function laporanlabarugi(Request $request){
        $perusahaan = auth()->user()->perusahaanUser;
        $bulan = $request->input('bulan', date('m'));
        $tahun = $request->input('tahun', date('Y'));
        return view('keuangan.labarugi', compact('perusahaan', 'bulan', 'tahun'));
    }
    public function laporanlabarugipdf(Request $request){

        // Ambil parameter filter
        $periode = $request->query('periode');
        $bulan = $request->query('bulan', date('m'));
        $tahun = $request->query('tahun', date('Y'));
        $tahun_bulan = $request->query('tahun_bulan');
        $tahun_tahun = $request->query('tahun_tahun');
        $tanggal_awal = $request->query('tanggal_awal');
        $tanggal_akhir = $request->query('tanggal_akhir');

        // Jika ada filter periode bulanan, gunakan bulan dan tahun_bulan
        if ($periode === 'bulanan' && $tahun_bulan) {
            $tahun = (int) $tahun_bulan;
        }

        // Jika ada filter periode tahunan, gunakan tahun_tahun dan set bulan ke null
        if ($periode === 'tahunan' && $tahun_tahun) {
            $tahun = (int) $tahun_tahun;
            $bulan = null;
        }

        // Ambil semua transaksi bulan ini beserta kedua akun
        $query = KeuanganTableku::with(['akun.kategori', 'akunSecond.kategori'])
            ->whereRaw("STR_TO_DATE(tanggal, '%d/%m/%Y') IS NOT NULL");

        // Filter berdasarkan tanggal_awal dan tanggal_akhir jika ada
        if ($tanggal_awal && $tanggal_akhir) {
            try {
                $fromDate = \Carbon\Carbon::createFromFormat('d/m/Y', $tanggal_awal)->format('Y-m-d');
                $toDate = \Carbon\Carbon::createFromFormat('d/m/Y', $tanggal_akhir)->format('Y-m-d');
                $query->whereRaw("STR_TO_DATE(tanggal, '%d/%m/%Y') BETWEEN ? AND ?", [$fromDate, $toDate]);
            } catch (\Exception $e) {
                // Abaikan jika format salah
            }
        } else {
            // Filter berdasarkan tahun
            $query->whereRaw("YEAR(STR_TO_DATE(tanggal, '%d/%m/%Y')) = ?", [$tahun]);

            // Filter berdasarkan bulan jika ada
            if ($bulan) {
                $query->whereRaw("MONTH(STR_TO_DATE(tanggal, '%d/%m/%Y')) = ?", [$bulan]);
            }
        }

        $items = $query->get();

        // Inisialisasi array Laba Rugi
        $labaRugi = [
            'pendapatan' => collect(),
            'hpp' => collect(),
            'beban_operasional' => collect(),
            'pendapatan_lainnya' => collect(),
            'beban_lainnya' => collect(),
        ];

        $addedAkun = []; // array untuk menjumlahkan total per akun

        // Loop semua transaksi untuk kumpulkan total per akun
        foreach ($items as $item) {
            $akunList = [$item->akun, $item->akunSecond];
            foreach ($akunList as $akun) {
                if (!$akun || !$akun->kategori) continue;

                $key = $akun->id;
                if (!isset($addedAkun[$key])) {
                    $addedAkun[$key] = 0;
                }
                $addedAkun[$key] += $item->total;
            }
        }

        // Mapping nama kategori ke tipe Laba Rugi
        $kategoriMap = [
            'Pendapatan' => 'pendapatan',
            'Pendapatan Lainnya' => 'pendapatan_lainnya',
            'Harga Pokok Penjualan' => 'hpp',
            'Beban' => 'beban_operasional',
            'Beban Lainnya' => 'beban_lainnya',
            'Depresiasi & Amortisasi' => 'beban_lainnya',
        ];

        // Fungsi helper push ke Laba Rugi
        $pushToLabaRugi = function($akun, $total) use (&$labaRugi, $kategoriMap) {
            $namaKategori = $akun->kategori->nama_kategori;
            $tipeKategori = $kategoriMap[$namaKategori] ?? null;

            if (!$tipeKategori) return; // abaikan jika bukan Laba Rugi

            $saldoItem = (object)[
                'nama_akun' => $akun->nama_akun,
                'saldo' => $total,
            ];

            $labaRugi[$tipeKategori]->push($saldoItem);
        };

        // Loop akun yang sudah dijumlahkan totalnya
        foreach ($addedAkun as $akunId => $total) {
            $akun = AkunTable::with('kategori')->find($akunId);
            if ($akun) {
                $pushToLabaRugi($akun, $total);
            }
        }

        // Hitung total Laba Rugi
        $totalPendapatan = $labaRugi['pendapatan']->sum('saldo');
        $totalHpp = $labaRugi['hpp']->sum('saldo');
        $totalBebanOperasional = $labaRugi['beban_operasional']->sum('saldo');
        $totalPendapatanLainnya = $labaRugi['pendapatan_lainnya']->sum('saldo');
        $totalBebanLainnya = $labaRugi['beban_lainnya']->sum('saldo');

        $labaRugi['laba_kotor'] = $totalPendapatan - $totalHpp;
        $labaRugi['laba_operasional'] = $labaRugi['laba_kotor'] - $totalBebanOperasional;
        $labaRugi['laba_bersih'] = $labaRugi['laba_operasional'] + $totalPendapatanLainnya - $totalBebanLainnya;

       $perusahaan = auth()->user()->perusahaanUser;

        $logo = null;
        if ($perusahaan->logo && file_exists(storage_path('app/public/' . $perusahaan->logo))) {
            $logoPath = storage_path('app/public/' . $perusahaan->logo);
            $logo = 'data:image/' . pathinfo($logoPath, PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents($logoPath));
        } else {
            // fallback ke logo default di public/assets
            $defaultPath = public_path('assets/default_logo.png');
            if (file_exists($defaultPath)) {
                $logo = 'data:image/png;base64,' . base64_encode(file_get_contents($defaultPath));
            }
        }

        // Generate PDF
        $pdf = Pdf::loadView('keuangan.pdf.labarugi', [
            'bulan' => $bulan,
            'tahun' => $tahun,
            'labaRugi' => $labaRugi,
            'bulan'       => $bulan,
            'tahun'       => $tahun,
            'perusahaan'  => $perusahaan,
            'logo'        => $logo,
            'periode'     => $periode,
            'tanggal_awal' => $tanggal_awal,
            'tanggal_akhir' => $tanggal_akhir,
        ])->setPaper('A4', 'portrait');

        return $pdf->stream("LAPORAN-LABA-RUGI-{$bulan}-{$tahun}.pdf");
    }
    public function laporanneraca(Request $request){

        $perusahaan = auth()->user()->perusahaanUser;
        $bulan = $request->input('bulan', date('m'));
        $tahun = $request->input('tahun', date('Y'));

        $pdf = Pdf::loadView('keuangan.pdf.labarugi', [
            'bulan' => $bulan,
            'tahun' => $tahun,
            'labaRugi' => $labaRugi,
            'bulan'       => $bulan,
            'tahun'       => $tahun,
            'perusahaan'  => $perusahaan,
            'logo'        => $logo,
        ])->setPaper('A4', 'portrait');

        return $pdf->stream("LAPORAN-NERACA-{$bulan}-{$tahun}.pdf");
    }
    

}
