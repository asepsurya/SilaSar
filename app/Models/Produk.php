<?php

namespace App\Models;

use App\Models\Satuan;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $guarded=['id']; 
    public function satuan()
    {
        return $this->belongsTo(Satuan::class, 'satuan_id', 'id');
    }
    // di StokItem.php
    public function satuanObj()
    {
        return $this->belongsTo(Satuan::class, 'satuan_id', 'id'); // 'satuan' = field di stok_items
    }

}
