<?php

namespace App\Http\Controllers\Api\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Resources\CourseResource;
use App\Models\Course;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TeacherCourseController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $courses = Course::query()
            ->where('teacher_id', $request->user()->id)
            ->withCount('lessons')
            ->latest()
            ->paginate($request->integer('per_page', 15));

        return $this->paginatedResponse('Daftar course berhasil diambil.', $courses, CourseResource::class);
    }

    public function show(Request $request, Course $course): JsonResponse
    {
        $this->ensureOwns($request, $course);

        return response()->json([
            'success' => true,
            'message' => 'Detail course berhasil diambil.',
            'data'    => new CourseResource($course->load('lessons')),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'level'       => ['nullable', 'string', 'max:100'],
            'is_active'   => ['nullable', 'boolean'],
            'thumbnail'   => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $validated['teacher_id'] = $request->user()->id;
        $validated['slug']       = $this->uniqueSlug($validated['title']);

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('courses', 'public');
        } else {
            unset($validated['thumbnail']);
        }

        $course = DB::transaction(fn (): Course => Course::query()->create($validated));

        return response()->json([
            'success' => true,
            'message' => 'Course berhasil dibuat.',
            'data'    => new CourseResource($course),
        ], 201);
    }

    public function update(Request $request, Course $course): JsonResponse
    {
        $this->ensureOwns($request, $course);

        $validated = $request->validate([
            'title'       => ['sometimes', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'level'       => ['nullable', 'string', 'max:100'],
            'is_active'   => ['nullable', 'boolean'],
            'thumbnail'   => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        if (isset($validated['title'])) {
            $validated['slug'] = $this->uniqueSlug($validated['title'], $course->id);
        }

        if ($request->hasFile('thumbnail')) {
            // Hapus file lama sebelum simpan yang baru
            if ($course->thumbnail) {
                Storage::disk('public')->delete($course->thumbnail);
            }
            $validated['thumbnail'] = $request->file('thumbnail')->store('courses', 'public');
        } else {
            unset($validated['thumbnail']);
        }

        DB::transaction(fn () => $course->update($validated));

        return response()->json([
            'success' => true,
            'message' => 'Course berhasil diperbarui.',
            'data'    => new CourseResource($course->refresh()->load('lessons')),
        ]);
    }

    public function destroy(Request $request, Course $course): JsonResponse
    {
        $this->ensureOwns($request, $course);

        DB::transaction(function () use ($course): void {
            if ($course->thumbnail) {
                Storage::disk('public')->delete($course->thumbnail);
            }
            $course->delete();
        });

        return response()->json([
            'success' => true,
            'message' => 'Course berhasil dihapus.',
            'data'    => null,
        ]);
    }

    private function ensureOwns(Request $request, Course $course): void
    {
        abort_if($course->teacher_id !== $request->user()->id, 403, 'Akses ditolak.');
    }

    private function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $slug = Str::slug($title);
        $query = Course::query()->where('slug', $slug);
        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }

        $count = 2;
        $original = $slug;
        while ($query->exists()) {
            $slug = $original . '-' . $count;
            $count++;
            $query = Course::query()->where('slug', $slug);
            if ($ignoreId) {
                $query->where('id', '!=', $ignoreId);
            }
        }

        return $slug;
    }
}
