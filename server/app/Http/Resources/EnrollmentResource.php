<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EnrollmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $totalLessons = 0;
        $completedLessons = 0;

        // Hitung lessons dari semua course yang ter-link ke package enrollment
        if ($this->relationLoaded('package') && $this->package) {
            $totalLessons = \App\Models\Lesson::active()
                ->whereHas('course', fn ($q) => $q->whereHas('packages', fn ($pq) =>
                    $pq->where('packages.id', $this->package_id)))
                ->count();

            $completedLessons = \App\Models\LessonProgress::where('user_id', $this->user_id)
                ->completed()
                ->whereHas('lesson.course.packages', fn ($pq) =>
                    $pq->where('packages.id', $this->package_id))
                ->count();
        }

        // Status "efektif" untuk frontend: active / completed / expired
        $effectiveStatus = 'active';
        if ($totalLessons > 0 && $completedLessons >= $totalLessons) {
            $effectiveStatus = 'completed';
        } elseif ($this->end_date && $this->end_date->isPast()) {
            $effectiveStatus = 'expired';
        }

        return [
            'id' => $this->id,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'status' => $this->status,
            'effective_status' => $effectiveStatus,
            'package' => new PackageResource($this->whenLoaded('package')),
            'progress' => [
                'current_lesson'  => $completedLessons,
                'total_lessons'   => $totalLessons,
                'percentage'      => $totalLessons > 0 ? round(($completedLessons / $totalLessons) * 100, 2) : 0,
            ],
        ];
    }
}