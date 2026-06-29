<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuizQuestion extends Model
{
    protected $fillable = [
        'quiz_id',
        'question',
        'options',
        'correct_answer',
        'explanation',
        'sort_order',
    ];

    protected $casts = [
        'options' => 'array',
        'sort_order' => 'integer',
    ];

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }
}
