<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuizAttemptResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'quiz_id'      => $this->quiz_id,
            'answers'      => $this->answers,
            'score'        => $this->score,
            'is_passed'    => $this->is_passed,
            'started_at'   => $this->started_at,
            'submitted_at' => $this->submitted_at,
            'created_at'   => $this->created_at,

            'quiz' => $this->whenLoaded('quiz', fn () => $this->quizData()),
        ];
    }

    private function quizData(): array
    {
        $quiz = $this->quiz;

        return [
            'id'            => $quiz->id,
            'course_id'     => $quiz->course_id,
            'lesson_id'     => $quiz->lesson_id,
            'title'         => $quiz->title,
            'description'   => $quiz->description,
            'passing_score' => $quiz->passing_score,

            'course' => $quiz->relationLoaded('course') && $quiz->course ? [
                'id'    => $quiz->course->id,
                'title' => $quiz->course->title,
                'slug'  => $quiz->course->slug,
            ] : null,

            // WAJIB dimuat agar halaman hasil bisa bikin "pembahasan jawaban"
            'questions' => $quiz->relationLoaded('questions')
                ? $quiz->questions->map(fn ($q) => [
                    'id'             => $q->id,
                    'question'       => $q->question,
                    'options'        => $q->options,
                    'correct_answer' => $q->correct_answer,
                    'explanation'    => $q->explanation,
                    'sort_order'     => $q->sort_order,
                ])->values()
                : [],
        ];
    }
}