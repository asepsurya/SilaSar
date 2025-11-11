<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KeuanganTable extends Model
{

    protected $table = 'keuangan_tables';
      protected $guarded=['id'];
    public function akun(){
        return $this->belongsTo('App\Models\AkunTable','id_akun','id');
    }
    public function rekening(){
        return $this->belongsTo('App\Models\RekeningTable','id_rekening','id');
    }

    public function akunSecond()
    {
        return $this->belongsTo(AkunTable::class, 'id_akun_second'); // <- ini wajib
    }
}
