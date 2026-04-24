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

    public function satuanObj()
    {
        return $this->belongsTo(Satuan::class, 'satuan_id', 'id');
    }
    public function getFirstImageAttribute()
    {
        if (!$this->gambar) return null;
        $decoded = json_decode($this->gambar, true);
        if (is_array($decoded) && count($decoded) > 0) {
            return $decoded[0];
        }
        return $this->gambar; // Fallback to single string
    }

    public function getAllImagesAttribute()
    {
        if (!$this->gambar) return [];
        $decoded = json_decode($this->gambar, true);
        return is_array($decoded) ? $decoded : [$this->gambar];
    }
}
