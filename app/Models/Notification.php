<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = ['user_id', 'transaksi_id', 'type', 'title', 'message', 'data', 'is_read', 'read_at', 'browser_notified', 'notified_at'];
    
    protected $casts = [
        'data' => 'array',
        'is_read' => 'boolean',
        'browser_notified' => 'boolean',
        'read_at' => 'datetime',
        'notified_at' => 'datetime',
    ];

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeType($query, $type)
    {
        return $query->where('type', $type);
    }
}
