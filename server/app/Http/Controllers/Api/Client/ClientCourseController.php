<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\CourseResource;
use App\Models\Course;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClientCourseController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $courses = Course::query()
            ->active()
            ->with(['lessons' => fn ($query) => $query->active()])
            ->paginate($request->integer('per_page', 15));

        return response()->json([
            'success' => true,
            'message' => 'Daftar course berhasil diambil.',
            'data' => CourseResource::collection($courses),
            'meta' => $this->paginationMeta($courses),
        ]);
    }

    public function show(Course $course): JsonResponse
    {
        abort_if(! $course->is_active, 404);

        return response()->json([
            'success' => true,
            'message' => 'Detail course berhasil diambil.',
            'data' => new CourseResource($course->load(['lessons' => fn ($query) => $query->active()])),
        ]);
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
