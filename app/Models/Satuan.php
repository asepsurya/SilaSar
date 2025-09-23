<?php

namespace App\Models;

use App\Models\Produk;
use Illuminate\Database\Eloquent\Model;

class Satuan extends Model
{
    protected $table = 'satuans'; // nama tabel
    protected $fillable = ['nama', 'keterangan'];

    // relasi ke produk
    public function satuan()
    {
        return $this->hasMany(Produk::class, 'satuan_id', 'id');
    }
    
}
