<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penawaran extends Model
{
    protected $guarded=['id']; 

    public function produk(){
        return $this->belongsTo('App\Models\Produk','kode_produk','kode_produk');
    }
}
