<?php

namespace App\Models;

use App\Models\Produk;
use Illuminate\Database\Eloquent\Model;

class StokLog extends Model
{
    protected $fillable = [
        'kode_produk',
        'tipe',
        'jumlah',
        'sumber',
        'referensi',
        'auth',
        'keterangan'
    ];

   public function produk()
    {
        return $this->belongsTo(Produk::class, 'kode_produk', 'kode_produk');
    }
}
