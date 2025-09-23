<?php

namespace App\Http\Controllers\Nota;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Dokumen;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Activitylog\Models\Activity;

class NotaController extends Controller
{
    public function nota(){
        $nota = Dokumen::where('auth',auth()->user()->id)->first();
        $data= Dokumen::where('auth',auth()->user()->id)->get();
        $logs = Activity::where(['causer_id'=>auth()->user()->id, 'log_name' => 'ikm'])->latest()->take(10)->get();
        return view('nota.index',[
            'activeMenu' => 'nota',
            'active' => 'nota', 
        ],compact('logs','nota','data'));
    }
    public function notaDelete ($id){
         Dokumen::where('id',$id)->delete();
         return redirect()->back();
    }

    public function nota2($id){
 // Data contoh untuk Blade
         $transaksi = Transaksi::where('kode_transaksi', $id)->first();

    $pdf = Pdf::loadView('report.sample', compact('transaksi'))
              ->setPaper('a4', 'portrait');

    return $pdf->download('invoice.pdf');
    }
}
