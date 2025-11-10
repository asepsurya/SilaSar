<?php

namespace App\Http\Controllers\Keuangan;

use Illuminate\Http\Request;
use App\Models\HistoryRekening;
use App\Http\Controllers\Controller;
use Spatie\Activitylog\Models\Activity;

class HistoryHarianController extends Controller
{
    public function rekeningHistory($id_rekening){
        $histories = HistoryRekening::where('id_rekening',$id_rekening)->get();
         $logs = Activity::where(['causer_id'=>auth()->user()->id, 'log_name' => 'ikm'])->latest()->take(10)->get();
        return view('keuangan.catatan_keuangan.rekeningHistory',[
            'activeMenu' => 'rekening_harian',
            'active' => 'rekening_harian',
        ],compact('histories','logs','id_rekening'));
    }
}
