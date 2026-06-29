<?php

namespace App\Http\Controllers\Api\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\Teacher\StoreLessonRequest;
use App\Http\Requests\Teacher\UpdateLessonRequest;
use App\Http\Resources\LessonResource;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeacherLessonController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $lessons = Lesson::query()
            ->whereHas('course', fn ($query) => $query->where('teacher_id', $request->user()->id))
            ->with('course')
            ->orderBy('course_id')
            ->orderBy('sort_order')
            ->paginate($request->integer('per_page', 15));

        return response()->json([
            'success' => true,
            'message' => 'Daftar lesson berhasil diambil.',
            'data' => LessonResource::collection($lessons),
            'meta' => $this->paginationMeta($lessons),
        ]);
    }

    public function show(Request $request, Lesson $lesson): JsonResponse
    {
        $this->ensureOwnsLesson($request, $lesson);

        return response()->json([
            'success' => true,
            'message' => 'Detail lesson berhasil diambil.',
            'data' => new LessonResource($lesson->load('course')),
        ]);
    }

    public function store(StoreLessonRequest $request): JsonResponse
    {
        $course = Course::query()->where('teacher_id', $request->user()->id)->findOrFail($request->validated('course_id'));

        $lesson = DB::transaction(fn (): Lesson => $course->lessons()->create($request->safe()->except('course_id')));

        return response()->json([
            'success' => true,
            'message' => 'Lesson berhasil dibuat.',
            'data' => new LessonResource($lesson->load('course')),
        ], 201);
    }

    public function update(UpdateLessonRequest $request, Lesson $lesson): JsonResponse
    {
        $this->ensureOwnsLesson($request, $lesson);

        DB::transaction(fn () => $lesson->update($request->validated()));

        return response()->json([
            'success' => true,
            'message' => 'Lesson berhasil diperbarui.',
            'data' => new LessonResource($lesson->refresh()->load('course')),
        ]);
    }

    public function destroy(Request $request, Lesson $lesson): JsonResponse
    {
        $this->ensureOwnsLesson($request, $lesson);

        DB::transaction(fn () => $lesson->delete());

        return response()->json([
            'success' => true,
            'message' => 'Lesson berhasil dihapus.',
            'data' => null,
        ]);
    }

    private function ensureOwnsLesson(Request $request, Lesson $lesson): void
    {
        abort_if($lesson->course()->where('teacher_id', $request->user()->id)->doesntExist(), 403);
    }

    private function paginationMeta($paginator): array
    {
        return [
            'current_page' => $paginator->currentPage(),
            'last_page' => $paginator->lastPage(),
            'per_page' => $paginator->perPage(),
            'total' => $paginator->total(),
        ];
    }
}
