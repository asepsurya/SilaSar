<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dokumen extends Model
{
    protected $guarded=['id']; 
    public function produk() {
        return $this->hasMany('App\Models\Itemdokumen', 'kode_transaksi', 'kode_transaksi');
    }

}
