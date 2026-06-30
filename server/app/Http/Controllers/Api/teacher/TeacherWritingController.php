<?php

namespace App\Http\Controllers\Api\Teacher;

use App\Http\Controllers\Controller;

use App\Http\Resources\WritingExerciseResource;
use App\Models\Course;
use App\Models\WritingExercise;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeacherWritingController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $exercises = WritingExercise::query()
            ->whereHas('course', fn ($q) => $q->where('teacher_id', $request->user()->id))
            ->with(['course', 'lesson'])
            ->latest()
            ->paginate($request->integer('per_page', 15));

        return $this->paginatedResponse('Daftar writing exercise berhasil diambil.', $exercises, WritingExerciseResource::class);
    }

    public function show(Request $request, WritingExercise $writingExercise): JsonResponse
    {
        $this->ensureOwnsExercise($request, $writingExercise);

        return response()->json(['success' => true, 'message' => 'Detail writing exercise berhasil diambil.', 'data' => new WritingExerciseResource($writingExercise->load(['course', 'lesson']))]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'course_id' => ['required', 'integer', 'exists:courses,id'],
            'lesson_id' => ['nullable', 'integer', 'exists:lessons,id'],
            'character_type' => ['required', 'in:hiragana,katakana,kanji'],
            'character' => ['required', 'string', 'max:255'],
            'stroke_order_image' => ['nullable', 'string', 'max:500'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        Course::query()->where('teacher_id', $request->user()->id)->findOrFail($validated['course_id']);

        $exercise = DB::transaction(fn(): WritingExercise => WritingExercise::query()->create($validated));

        return response()->json(['success' => true, 'message' => 'Writing exercise berhasil dibuat.', 'data' => new WritingExerciseResource($exercise->load(['course', 'lesson']))], 201);
    }
    public function update(Request $request, WritingExercise $writingExercise): JsonResponse
    {
        $this->ensureOwnsExercise($request, $writingExercise);

        $validated = $request->validate([
            'course_id' => ['sometimes', 'integer', 'exists:courses,id'],
            'lesson_id' => ['nullable', 'integer', 'exists:lessons,id'],
            'character_type' => ['sometimes', 'in:hiragana,katakana,kanji'],
            'character' => ['sometimes', 'string', 'max:255'],
            'stroke_order_image' => ['nullable', 'string', 'max:500'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if (isset($validated['course_id'])) {
            Course::query()->where('teacher_id', $request->user()->id)->findOrFail($validated['course_id']);
        }

        DB::transaction(fn() => $writingExercise->update($validated));

        return response()->json(['success' => true, 'message' => 'Writing exercise berhasil diperbarui.', 'data' => new WritingExerciseResource($writingExercise->refresh()->load(['course', 'lesson']))]);
    }

    public function destroy(Request $request, WritingExercise $writingExercise): JsonResponse
    {
        $this->ensureOwnsExercise($request, $writingExercise);

        DB::transaction(fn() => $writingExercise->delete());

        return response()->json(['success' => true, 'message' => 'Writing exercise berhasil dihapus.', 'data' => null]);
    }

    private function ensureOwnsExercise(Request $request, WritingExercise $exercise): void
    {
        abort_if($exercise->course()->where('teacher_id', $request->user()->id)->doesntExist(), 403);
    }
}
