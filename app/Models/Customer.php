<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Customer extends Model
{
    protected $guarded = ['id'];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'monthly_fee' => 'decimal:2',
        'installation_date' => 'date',
        'expiry_date' => 'date',
        'last_login' => 'datetime',
        'last_logout' => 'datetime',
        'only_one_override' => 'boolean',
        'profile_override' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Relationship with PPP Profile
     */
    public function pppProfile()
    {
        return $this->belongsTo(PppProfile::class);
    }

    /**
     * Billing & Accounting relationships
     */
    public function customerPackages(): HasMany
    {
        return $this->hasMany(CustomerPackages::class, 'customer_id');
    }

    public function latestCustomerPackage(): HasOne
    {
        return $this->hasOne(CustomerPackages::class)->latestOfMany();
    }

    public function activePackage(): HasOne
    {
        return $this->hasOne(CustomerPackages::class)->where('status', customerPackages::STATUS_ACTIVE);
    }


    public function invoices(): HasManyThrough
    {
        return $this->hasManyThrough(
            Invoices::class,
            CustomerPackages::class,
            'customer_id',            // Foreign key on customer_packages
            'customer_package_id',    // Foreign key on invoices
            'id',
            'id'
        );
    }

    public function payments(): HasManyThrough
    {
        return $this->hasManyThrough(
            Payment::class,
            Invoices::class,
            'customer_package_id',
            'invoice_id',
            'id',
            'id'
        );
    }

    public function customerDiscounts(): HasMany
    {
        return $this->hasMany(CustomerDiscount::class);
    }

    public function discounts(): BelongsToMany
    {
        return $this->belongsToMany(Discount::class, 'customer_discounts')
            ->withPivot(['is_active', 'applied_at', 'created_at', 'updated_at'])
            ->withTimestamps();
    }

    public function primaryDiscount(): BelongsTo
    {
        return $this->belongsTo(Discount::class, 'discount_id');
    }

    public function credits(): HasMany
    {
        return $this->hasMany(CustomerCredit::class);
    }

    public function receivables(): HasMany
    {
        return $this->hasMany(AccountReceivable::class);
    }

    /**
     * Automatically hash password when setting
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = $value;
        // $this->attributes['password'] = Hash::make($value);
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active')->where('is_active', true);
    }

    public function scopeSuspended($query)
    {
        return $query->where('status', 'suspended');
    }

    public function scopeOverdue($query)
    {
        return $query->where('payment_status', 'overdue');
    }

    public function scopeExpiringSoon($query, $days = 7)
    {
        return $query->where('expiry_date', '<=', now()->addDays($days));
    }

    public function scopeCategory($query, string $category)
    {
        return $query->where('customer_category', $category);
    }

    /**
     * Get effective settings (profile or override)
     */
    public function getEffectiveRateLimit()
    {
        if ($this->profile_override && $this->rate_limit) {
            return $this->rate_limit;
        }
        return $this->pppProfile->rate_limit ?? null;
    }

    public function getEffectiveSessionTimeout()
    {
        if ($this->profile_override && $this->session_timeout_override) {
            return $this->session_timeout_override;
        }
        return $this->pppProfile->session_timeout ?? null;
    }

    public function getEffectiveIdleTimeout()
    {
        if ($this->profile_override && $this->idle_timeout_override) {
            return $this->idle_timeout_override;
        }
        return $this->pppProfile->idle_timeout ?? null;
    }

    public function getEffectiveOnlyOne()
    {
        if ($this->profile_override && $this->only_one_override !== null) {
            return $this->only_one_override;
        }
        return $this->pppProfile->only_one ?? false;
    }

    /**
     * Check if customer is expired
     */
    public function getIsExpiredAttribute()
    {
        return $this->expiry_date && $this->expiry_date->isPast();
    }

    /**
     * Get days until expiry
     */
    public function getDaysUntilExpiryAttribute()
    {
        if (!$this->expiry_date) {
            return null;
        }
        return now()->diffInDays($this->expiry_date, false);
    }

    /**
     * Format bytes for display
     */
    public function getFormattedBytesInAttribute()
    {
        return $this->formatBytes($this->bytes_in);
    }

    public function getFormattedBytesOutAttribute()
    {
        return $this->formatBytes($this->bytes_out);
    }

    public function getFormattedTotalBytesAttribute()
    {
        return $this->formatBytes($this->bytes_in + $this->bytes_out);
    }

    /**
     * Format uptime for display
     */
    public function getFormattedUptimeAttribute()
    {
        $seconds = $this->total_uptime;
        $days = floor($seconds / 86400);
        $hours = floor(($seconds % 86400) / 3600);
        $minutes = floor(($seconds % 3600) / 60);

        return "{$days}d {$hours}h {$minutes}m";
    }

    /**
     * Generate MikroTik PPP secret entry
     */
    public function toMikroTikSecret()
    {
        $script = "/ppp secret\n";
        $script .= "add name=\"{$this->username}\"";
        $script .= " password=\"{$this->getOriginal('password')}\""; // Get unhashed password
        $script .= " profile=\"{$this->ppp_profile}\"";

        if ($this->profile_override) {
            if ($this->local_address) {
                $script .= " local-address={$this->local_address}";
            }
            if ($this->remote_address) {
                $script .= " remote-address={$this->remote_address}";
            }
            if ($this->rate_limit) {
                $script .= " rate-limit={$this->rate_limit}";
            }
        }

        if ($this->caller_id) {
            $script .= " caller-id=\"{$this->caller_id}\"";
        }

        if (!$this->is_active || $this->status !== 'active') {
            $script .= " disabled=yes";
        }

        return $script;
    }

    protected static function booted()
    {
        static::creating(function ($record) {
            $record->billing_number = $record->billing_number ?? fake()->unique()->numerify('#######');
            if (empty($record->customer_category)) {
                $record->customer_category = 'normal';
            }
        });
    }

    /**
     * Helper method to format bytes
     */
    private function formatBytes($bytes)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $i = 0;

        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }
}
