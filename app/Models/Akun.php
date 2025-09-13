<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Akun extends Model
{
    protected $guarded=['id']; 
    public function kategori()
    {
        return $this->belongsTo(KategoriAkun::class, 'kategori_id');
    }
}
