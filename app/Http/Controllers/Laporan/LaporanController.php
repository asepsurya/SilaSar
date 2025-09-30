<?php

namespace App\Http\Controllers\Laporan;

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

}
