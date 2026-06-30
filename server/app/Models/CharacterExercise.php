<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CharacterExercise extends Model
{
    protected $fillable = [
        'course_id',
        'lesson_id',
        'character_type',
        'character',
        'answer',
        'options',
        'hint',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'options' => 'array',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(CharacterAttempt::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeType(Builder $query, string $type): Builder
    {
        return $query->where('character_type', $type);
    }
}
