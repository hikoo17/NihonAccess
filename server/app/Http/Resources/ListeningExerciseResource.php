<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ListeningExerciseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'course_id'   => $this->course_id,
            'lesson_id'   => $this->lesson_id,
            'title'       => $this->title,
            'description' => $this->description,
            'audio_url'   => $this->audio_url,
            'is_active'   => $this->is_active,
            'created_at'  => $this->created_at,
            'updated_at'  => $this->updated_at,
            'course' => $this->whenLoaded('course', fn () => [
                'id'    => $this->course->id,
                'title' => $this->course->title,
                'slug'  => $this->course->slug,
            ]),
            'lesson' => $this->whenLoaded('lesson', fn () => [
                'id'    => $this->lesson->id,
                'title' => $this->lesson->title,
            ]),
            'questions' => $this->whenLoaded('questions', fn () => $this->questions->map(fn ($q) => [
                'id'             => $q->id,
                'question'       => $q->question,
                'options'        => $q->options,
                'correct_answer' => $q->correct_answer,
                'sort_order'     => $q->sort_order,
            ])->values()),
        ];
    }
}