<?php

namespace App\Http\Controllers\Api\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Resources\LessonResource;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TeacherLessonController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $lessons = Lesson::query()
            ->whereHas('course', fn ($q) => $q->where('teacher_id', $request->user()->id))
            ->with('course')
            ->when($request->filled('course_id'), fn ($q) => $q->where('course_id', $request->integer('course_id')))
            ->orderBy('course_id')
            ->orderBy('sort_order')
            ->paginate($request->integer('per_page', 15));

        return $this->paginatedResponse('Daftar lesson berhasil diambil.', $lessons, LessonResource::class);
    }

    public function show(Request $request, Lesson $lesson): JsonResponse
    {
        $this->ensureOwns($request, $lesson);

        return response()->json([
            'success' => true,
            'message' => 'Detail lesson berhasil diambil.',
            'data'    => new LessonResource($lesson->load('course')),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'course_id'  => ['required', 'integer', 'exists:courses,id'],
            'title'      => ['required', 'string', 'max:255'],
            'content'    => ['nullable', 'string'],
            'video_url'  => ['nullable', 'url', 'max:500'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active'  => ['nullable', 'boolean'],
        ]);

        $course = Course::query()
            ->where('teacher_id', $request->user()->id)
            ->findOrFail($validated['course_id']);

        // Slug di-generate otomatis, unique per course
        $validated['slug'] = $this->uniqueSlug($validated['title'], $course->id);

        $lesson = DB::transaction(fn (): Lesson => $course->lessons()->create(
            collect($validated)->except('course_id')->toArray()
        ));

        return response()->json([
            'success' => true,
            'message' => 'Lesson berhasil dibuat.',
            'data'    => new LessonResource($lesson->load('course')),
        ], 201);
    }

    public function update(Request $request, Lesson $lesson): JsonResponse
    {
        $this->ensureOwns($request, $lesson);

        $validated = $request->validate([
            'title'      => ['sometimes', 'string', 'max:255'],
            'content'    => ['nullable', 'string'],
            'video_url'  => ['nullable', 'url', 'max:500'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active'  => ['nullable', 'boolean'],
        ]);

        if (isset($validated['title'])) {
            $validated['slug'] = $this->uniqueSlug($validated['title'], $lesson->course_id, $lesson->id);
        }

        DB::transaction(fn () => $lesson->update($validated));

        return response()->json([
            'success' => true,
            'message' => 'Lesson berhasil diperbarui.',
            'data'    => new LessonResource($lesson->refresh()->load('course')),
        ]);
    }

    public function destroy(Request $request, Lesson $lesson): JsonResponse
    {
        $this->ensureOwns($request, $lesson);

        DB::transaction(fn () => $lesson->delete());

        return response()->json([
            'success' => true,
            'message' => 'Lesson berhasil dihapus.',
            'data'    => null,
        ]);
    }

    private function ensureOwns(Request $request, Lesson $lesson): void
    {
        abort_if(
            $lesson->course()->where('teacher_id', $request->user()->id)->doesntExist(),
            403,
            'Akses ditolak.'
        );
    }

    private function uniqueSlug(string $title, int $courseId, ?int $ignoreId = null): string
    {
        $slug  = Str::slug($title);
        $query = Lesson::query()->where('course_id', $courseId)->where('slug', $slug);

        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }

        if ($query->exists()) {
            $slug .= '-' . time();
        }

        return $slug;
    }
}