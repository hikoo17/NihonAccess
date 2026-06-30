<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WritingExerciseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                 => $this->id,
            'course_id'          => $this->course_id,
            'lesson_id'          => $this->lesson_id,
            'character_type'     => $this->character_type,
            'character'          => $this->character,
            'stroke_order_image' => $this->stroke_order_image,
            'is_active'          => $this->is_active,
            'created_at'         => $this->created_at,
            'updated_at'         => $this->updated_at,
            'course' => $this->whenLoaded('course', fn () => [
                'id'    => $this->course->id,
                'title' => $this->course->title,
                'slug'  => $this->course->slug,
            ]),
            'lesson' => $this->whenLoaded('lesson', fn () => [
                'id'    => $this->lesson->id,
                'title' => $this->lesson->title,
            ]),
        ];
    }
}