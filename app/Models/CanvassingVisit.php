<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CanvassingVisit extends Model
{
    protected $guarded = ['id'];

    public function mitra()
    {
        return $this->belongsTo(Mitra::class);
    }
}
