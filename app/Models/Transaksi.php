<?php

namespace App\Models;

use App\Models\TransaksiProduct;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Transaksi extends Model
{
     protected $guarded=['id'];

     protected static function booted()
     {
         static::creating(function ($transaksi) {
             if (empty($transaksi->kode_transaksi)) {
                 $transaksi->kode_transaksi = static::generateUniqueKode();
             }
         });
     }

     private static function generateUniqueKode(): string
     {
         do {
             $year = date('Y');
             $random = random_int(100, 999);
             $kode = "B{$year}{$random}";
         } while (static::where('kode_transaksi', $kode)->exists());

         return $kode;
     }

     public function mitra(){
         return $this->belongsTo('App\Models\Mitra','kode_mitra','kode_mitra');
     }
     public function penawaran(){
         return $this->hasMany('App\Models\Penawaran','kode_mitra','kode_mitra');
     }
     public function perusahaan(){
         return $this->belongsTo('App\Models\Perusahaan','auth','auth');
     }
     public function ProdukTransaksi(){
         return $this->hasMany('App\Models\TransaksiProduct','kode_transaksi','kode_transaksi');
     }
     
     public function penawaran2(){
         return $this->belongsTo('App\Models\Penawaran','kode_mitra','kode_mitra');
     }

     public function items()
     {
         return $this->hasMany(TransaksiProduct::class, 'kode_transaksi', 'kode_transaksi');
     }

}
