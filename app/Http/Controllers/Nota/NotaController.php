<?php

namespace App\Http\Controllers\Nota;

use App\Models\Dokumen;
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
}
