<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\UpdateLessonProgressRequest;
use App\Http\Resources\LessonProgressResource;
use App\Http\Resources\LessonResource;
use App\Models\Lesson;
use App\Models\LessonProgress;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class ClientLessonController extends Controller
{
    public function show(Lesson $lesson): JsonResponse
    {
        abort_if(! $lesson->is_active || ! $lesson->course?->is_active, 404);

        return response()->json([
            'success' => true,
            'message' => 'Detail lesson berhasil diambil.',
            'data' => new LessonResource($lesson->load('course')),
        ]);
    }

    public function updateProgress(UpdateLessonProgressRequest $request, Lesson $lesson): JsonResponse
    {
        abort_if(! $lesson->is_active || ! $lesson->course?->is_active, 404);

        $progress = DB::transaction(function () use ($request, $lesson): LessonProgress {
            $isCompleted = (bool) $request->validated('is_completed');

            return LessonProgress::query()->updateOrCreate(
                ['user_id' => $request->user()->id, 'lesson_id' => $lesson->id],
                ['is_completed' => $isCompleted, 'completed_at' => $isCompleted ? now() : null],
            );
        });

        return response()->json([
            'success' => true,
            'message' => 'Progress lesson berhasil diperbarui.',
            'data' => new LessonProgressResource($progress),
        ]);
    }
}
