<?php

namespace App\Models;

use App\Models\StokTransaksi;
use Illuminate\Database\Eloquent\Model;

class StokItem extends Model
{
    protected $fillable = [
        'stok_transaksi_id', 'kode_produk', 'nama_produk', 'jumlah', 'satuan', 'harga', 'pot', 'total'
    ];

    public function transaksi()
    {
        return $this->belongsTo(StokTransaksi::class, 'stok_transaksi_id');
    }
}
