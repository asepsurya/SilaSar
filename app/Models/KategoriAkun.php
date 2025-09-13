<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriAkun extends Model
{
    protected $guarded=['id']; 
    public function akuns()
    {
        return $this->hasMany(Akun::class, 'kategori_id');
    }
}
