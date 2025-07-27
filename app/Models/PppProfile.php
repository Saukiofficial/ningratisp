<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PppProfile extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'only_one' => 'boolean',
        'is_active' => 'boolean',
        'session_timeout' => 'integer',
        'idle_timeout' => 'integer',
        'keepalive_timeout' => 'integer',
        'bridge_horizon' => 'integer',
        'bridge_path_cost' => 'integer',
        'bridge_port_priority' => 'integer',
    ];

    /**
     * Relationship with customers
     */
    public function customers()
    {
        return $this->hasMany(Customer::class,);
    }

    /**
     * Scope for active profiles
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get formatted rate limit for display
     */
    public function getFormattedRateLimitAttribute()
    {
        if (!$this->rate_limit) {
            return 'Unlimited';
        }

        $parts = explode('/', $this->rate_limit);
        if (count($parts) == 2) {
            return "↑{$parts[0]} / ↓{$parts[1]}";
        }

        return $this->rate_limit;
    }

    /**
     * Generate MikroTik script for this profile
     */
    public function toMikroTikScript()
    {
        $script = "/ppp profile\n";
        $script .= "add name=\"{$this->profile_name}\"";

        if ($this->local_address) {
            $script .= " local-address={$this->local_address}";
        }

        if ($this->remote_address) {
            $script .= " remote-address={$this->remote_address}";
        }

        if ($this->dns_server) {
            $script .= " dns-server={$this->dns_server}";
        }

        if ($this->rate_limit) {
            $script .= " rate-limit={$this->rate_limit}";
        }

        if ($this->session_timeout) {
            $script .= " session-timeout={$this->session_timeout}";
        }

        if ($this->idle_timeout) {
            $script .= " idle-timeout={$this->idle_timeout}";
        }

        if ($this->only_one) {
            $script .= " only-one=yes";
        }

        return $script;
    }
}
