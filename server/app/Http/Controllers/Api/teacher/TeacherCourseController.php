<?php

namespace App\Http\Controllers\Api\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Resources\CourseResource;
use App\Models\Course;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TeacherCourseController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $courses = Course::query()
            ->where('teacher_id', $request->user()->id)
            ->with('lessons')
            ->paginate($request->integer('per_page', 15));

        return response()->json([
            'success' => true,
            'message' => 'Daftar course yang diajar berhasil diambil.',
            'data' => CourseResource::collection($courses),
            'meta' => $this->paginationMeta($courses),
        ]);
    }

    public function show(Request $request, Course $course): JsonResponse
    {
        $this->ensureOwnsCourse($request, $course);

        return response()->json([
            'success' => true,
            'message' => 'Detail course berhasil diambil.',
            'data' => new CourseResource($course->load('lessons')),
        ]);
    }

    private function ensureOwnsCourse(Request $request, Course $course): void
    {
        abort_if($course->teacher_id !== $request->user()->id, 403);
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
