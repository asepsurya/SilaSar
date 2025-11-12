<?php

namespace App\Models;

use App\Models\Transaksi;
use Illuminate\Database\Eloquent\Model;

class TransaksiProduct extends Model
{
    protected $guarded=['id'];
    
    public function penawaran(){
        return $this->belongsTo('App\Models\Penawaran','kode_produk','kode_produk');
    }
    public function produk(){
        return $this->belongsTo('App\Models\Produk','kode_produk','kode_produk');
    } 

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'kode_transaksi', 'kode_transaksi');
    }

}