<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class AiGeneration extends Model
{
    protected $fillable = [
        'uuid',
        'user_id',
        'type',
        'input',
        'status',
        'result',
        'error_message',
    ];

    protected $casts = [
        'input'  => 'array',
        'result' => 'array',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $generation) {
            $generation->uuid ??= (string) Str::uuid();
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}