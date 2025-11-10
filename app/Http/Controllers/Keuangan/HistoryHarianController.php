<?php

namespace App\Http\Controllers\Keuangan;

use Illuminate\Http\Request;
use App\Models\HistoryRekening;
use App\Http\Controllers\Controller;
use Spatie\Activitylog\Models\Activity;

class HistoryHarianController extends Controller
{
    public function rekeningHistory(Request $request, $id_rekening){
        $histories = HistoryRekening::where('id_rekening', $id_rekening);

        // Filter berdasarkan periode waktu
        if ($request->periode) {
            switch ($request->periode) {
                case 'bulanan':
                    if ($request->bulan && $request->tahun_bulan) {
                        $histories->whereMonth('created_at', $request->bulan)
                                  ->whereYear('created_at', $request->tahun_bulan);
                    }
                    break;
                case 'tahunan':
                    if ($request->tahun_tahun) {
                        $histories->whereYear('created_at', $request->tahun_tahun);
                    }
                    break;
                case 'rentang':
                    if ($request->tanggal_awal && $request->tanggal_akhir) {
                        $tanggal_awal = \Carbon\Carbon::createFromFormat('d/m/Y', $request->tanggal_awal)->format('Y-m-d');
                        $tanggal_akhir = \Carbon\Carbon::createFromFormat('d/m/Y', $request->tanggal_akhir)->format('Y-m-d');
                        $histories->whereBetween('created_at', [$tanggal_awal, $tanggal_akhir]);
                    }
                    break;
            }
        }

        $histories = $histories->orderBy('created_at', 'desc')->get();
        $logs = Activity::where(['causer_id' => auth()->user()->id, 'log_name' => 'ikm'])->latest()->take(10)->get();
        return view('keuangan.catatan_keuangan.rekeningHistory', [
            'activeMenu' => 'rekening_harian',
            'active' => 'rekening_harian',
        ], compact('histories', 'logs', 'id_rekening'));
    }
}
