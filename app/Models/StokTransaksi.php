<?php

namespace App\Models;

use App\Models\StokItem;
use Illuminate\Database\Eloquent\Model;

class StokTransaksi extends Model
{
    
    protected $fillable = [
        'no_transaksi', 'tanggal', 'deskripsi', 'subtotal', 'potongan', 'pajak', 'total_akhir'
    ];

    public function items()
    {
        return $this->hasMany(StokItem::class);
    }
}
