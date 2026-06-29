<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ListeningQuestion extends Model
{
    protected $fillable = [
        'listening_exercise_id',
        'question',
        'options',
        'correct_answer',
        'sort_order',
    ];

    protected $casts = [
        'options' => 'array',
        'sort_order' => 'integer',
    ];

    public function exercise(): BelongsTo
    {
        return $this->belongsTo(ListeningExercise::class, 'listening_exercise_id');
    }
}
