<?php

namespace App\Http\Controllers\Laporan;

use App\Models\Keuangan;
use App\Models\Transaksi;
use Illuminate\Http\Request;
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

        $bulan = $request->query('bulan', date('m'));
        $tahun = $request->query('tahun', date('Y'));
        // Ambil semua transaksi bulan ini beserta kedua akun
        $items = Keuangan::with(['akun.kategori', 'akunSecond.kategori'])
            ->whereRaw("MONTH(STR_TO_DATE(tanggal, '%d/%m/%Y')) = ?", [$bulan])
            ->whereRaw("YEAR(STR_TO_DATE(tanggal, '%d/%m/%Y')) = ?", [$tahun])
            ->get();

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
            $akun = Akun::with('kategori')->find($akunId);
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
