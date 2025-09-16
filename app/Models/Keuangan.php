<?php

namespace App\Models;

use App\Models\Akun;
use Illuminate\Database\Eloquent\Model;

class Keuangan extends Model
{
    protected $guarded=['id']; 

    public function akun(){
        return $this->belongsTo('App\Models\Akun','id_akun','id');
    }
    public function rekening(){
        return $this->belongsTo('App\Models\Rekening','id_rekening','id');
    }

    public function akunSecond()
    {
        return $this->belongsTo(Akun::class, 'id_akun_second'); // <- ini wajib
    }
}
