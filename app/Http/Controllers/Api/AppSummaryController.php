<?php

namespace App\Http\Controllers\Api;

use App\Models\Mitra;
use App\Models\Produk;
use App\Models\CanvassingVisit;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class AppSummaryController extends Controller
{
    public function getSummary()
    {
        $userId = auth()->id();

        // 1. Mitra Summary
        $totalMitra = Mitra::where('auth', $userId)->count();
        $mitraByKota = Mitra::where('auth', $userId)
            ->whereNotNull('id_kota')
            ->select('id_kota', DB::raw('count(*) as total'))
            ->groupBy('id_kota')
            ->orderBy('total', 'desc')
            ->get();

        // 2. Canvassing Summary (Toko)
        $allToko = Mitra::where('auth', $userId)->get();
        $checkedThisMonth = CanvassingVisit::where('user_id', $userId)
            ->whereYear('visited_at', now()->year)
            ->whereMonth('visited_at', now()->month)
            ->pluck('mitra_id')
            ->toArray();

        $tokoChecked = 0;
        $tokoUnchecked = 0;
        $tokoInBandung = [];
        $tokoBelumCek = [];

        foreach ($allToko as $t) {
            $isChecked = in_array($t->id, $checkedThisMonth);
            if ($isChecked) {
                $tokoChecked++;
            } else {
                $tokoUnchecked++;
                $tokoBelumCek[] = $t->nama_mitra;
            }

            // Check for Bandung
            if (stripos($t->id_kota, 'Bandung') !== false || stripos($t->alamat_mitra, 'Bandung') !== false) {
                $tokoInBandung[] = $t->nama_mitra;
            }
        }

        // 3. Stock Summary
        $totalProduk = Produk::where('auth', $userId)->count();
        $stokMenipis = Produk::where('auth', $userId)
            ->whereRaw('stok <= 5') // Contoh ambang batas
            ->select('nama_produk', 'stok')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'mitra' => [
                    'total' => $totalMitra,
                    'by_kota' => $mitraByKota
                ],
                'canvassing' => [
                    'total_toko' => $allToko->count(),
                    'checked_this_month' => $tokoChecked,
                    'unchecked_this_month' => $tokoUnchecked,
                    'toko_bandung' => array_slice($tokoInBandung, 0, 10),
                    'toko_belum_cek' => array_slice($tokoBelumCek, 0, 10),
                ],
                'produk' => [
                    'total' => $totalProduk,
                    'stok_menipis' => $stokMenipis
                ]
            ]
        ]);
    }
}
