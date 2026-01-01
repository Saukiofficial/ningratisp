<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RouterProxy extends Model
{
    protected $fillable = [
        'customer_id',
        'router_id',
        'router_ip',
        'router_port',
        'is_active',
        'last_accessed_at'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'last_accessed_at' => 'datetime'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function getProxyUrlAttribute()
    {
        return config('zerotier.proxy_url', 'http://localhost') . '/router/' . $this->router_id . '/';
    }
}
