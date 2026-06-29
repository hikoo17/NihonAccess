<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CharacterAttempt extends Model
{
    protected $fillable = [
        'user_id',
        'character_exercise_id',
        'answer',
        'is_correct',
        'submitted_at',
    ];

    protected $casts = [
        'is_correct' => 'boolean',
        'submitted_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function exercise(): BelongsTo
    {
        return $this->belongsTo(CharacterExercise::class, 'character_exercise_id');
    }
}
