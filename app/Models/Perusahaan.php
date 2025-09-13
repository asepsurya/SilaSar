<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Perusahaan extends Model
{
   protected $guarded=['id'];
   public function legalitas(){
        return $this->hasMany('App\Models\Legalitas','id_perusahaan','id');
    }
       public function kota(){
        return $this->belongsTo('App\Models\Regency','id_kota','id');
    }
    public function kecamatan(){
        return $this->belongsTo('App\Models\District','id_kecamatan','id');
    }
    public function desa(){
        return $this->belongsTo('App\Models\Village','id_desa','id');
    }
}
