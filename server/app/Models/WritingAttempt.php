<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WritingAttempt extends Model
{
    protected $fillable = [
        'user_id',
        'writing_exercise_id',
        'submission_image',
        'metadata',
        'score',
        'submitted_at',
    ];

    protected $casts = [
        'metadata' => 'array',
        'score' => 'integer',
        'submitted_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function exercise(): BelongsTo
    {
        return $this->belongsTo(WritingExercise::class, 'writing_exercise_id');
    }
}
