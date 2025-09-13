<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mitra extends Model
{
    protected $guarded=['id']; 
    public function transaksi(){
        return $this->belongsTo('App\Models\Transaksi','kode_mitra','kode_mitra');
    }
}
