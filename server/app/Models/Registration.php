<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Registration extends Model
{
    protected $fillable = [
        'full_name',
        'email',
        'phone',
        'status',
        'account_created_at',
    ];

    protected $casts = [
        'account_created_at' => 'datetime',
    ];

    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }

    public function orders(): HasMany
    {
        return $this->hasManyThrough(Order::class, User::class);
    }
}
