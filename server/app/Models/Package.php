<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Package extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'type',
        'icon',
        'badge',
        'format',
        'description',
        'level',
        'price',
        'price_note',
        'duration_days',
        'highlights',
        'modules',
        'suitable_for',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'duration_days' => 'integer',
        'is_active' => 'boolean',
        'highlights' => 'array',
        'modules' => 'array',
        'suitable_for' => 'array',
    ];

    protected $appends = ['price_formatted', 'duration_label'];

    public function features(): HasMany
    {
        return $this->hasMany(PackageFeature::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeOnline(Builder $query): Builder
    {
        return $query->where('type', 'online');
    }

    public function scopeOnsite(Builder $query): Builder
    {
        return $query->where('type', 'onsite');
    }

    public function getPriceFormattedAttribute(): string
    {
        if ((int) $this->price === 0) {
            return 'Hubungi Kami';
        }

        return 'Rp' . number_format((float) $this->price, 0, ',', '.');
    }

    public function getDurationLabelAttribute(): string
    {
        if ($this->duration_days <= 0) {
            return 'Custom';
        }

        if ($this->duration_days % 30 === 0) {
            $months = $this->duration_days / 30;

            return $months . ' bulan';
        }

        return $this->duration_days . ' hari';
    }
}
