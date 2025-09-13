<?php

namespace App\Models;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;

class ikm extends Model
{
   
    protected static $logName = 'ikm';
    protected $guarded=['id']; 

    public function User(){
        return $this->belongsTo('App\Models\User','email','email');
    }
    public function kota(){
        return $this->belongsTo('App\Models\Regency','id_kota','id');
    }
    public function kecamatan(){
        return $this->belongsTo('App\Models\District','id_kecamatan','id');
    }
    public function desa(){
        return $this->belongsTo('App\Models\Village','id_desa','id');
    }
 
}
