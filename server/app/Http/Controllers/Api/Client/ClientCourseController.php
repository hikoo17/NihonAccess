<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Resources\EnrollmentResource;
use App\Http\Controllers\Controller;
use App\Http\Resources\CourseResource;
use App\Models\Course;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\LessonProgress;

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

    public function myCourses(Request $request): JsonResponse
{
    $user = $request->user();

    // Ambil SEMUA enrollment user (bukan hanya aktif),
    // beserta relasi package-nya, urut dari terbaru.
    $enrollments = $user->enrollments()
        ->with('package')
        ->latest('start_date')
        ->get();

    return response()->json([
        'success' => true,
        'message' => 'Daftar kursus saya berhasil diambil.',
        'data' => EnrollmentResource::collection($enrollments),
    ]);
}

/**
 * Ambil daftar lesson dari sebuah enrollment (halaman belajar).
 */
public function lessons(Request $request, Enrollment $enrollment): JsonResponse
{
    $user = $request->user();

    // Pastikan enrollment milik user yang login
    if ($enrollment->user_id !== $user->id) {
        abort(403, 'Kursus ini bukan milik Anda.');
    }

    $enrollment->load('package');

    // Semua lesson aktif dari course yang ter-link ke package enrollment
    $lessons = Lesson::active()
        ->whereHas('course.packages', fn ($q) => $q->where('packages.id', $enrollment->package_id))
        ->orderBy('sort_order')
        ->orderBy('id')
        ->get();

    // Ambil daftar lesson yang sudah diselesaikan user
    $completedIds = LessonProgress::where('user_id', $user->id)
        ->where('is_completed', true)
        ->whereIn('lesson_id', $lessons->pluck('id'))
        ->pluck('lesson_id');

    return response()->json([
        'success' => true,
        'message' => 'Daftar pelajaran berhasil diambil.',
        'data' => [
            'title'   => $enrollment->package?->name ?? 'Kursus',
            'lessons' => $lessons->map(fn ($lesson) => [
                'id'         => $lesson->id,
                'title'      => $lesson->title,
                'slug'       => $lesson->slug,
                'content'    => $lesson->content,
                'video_url'  => $lesson->video_url,
                'sort_order' => $lesson->sort_order,
                'completed'  => $completedIds->contains($lesson->id),
            ]),
        ],
    ]);
}

/**
 * Tandai sebuah lesson sebagai selesai.
 */
public function completeLesson(Request $request, Lesson $lesson): JsonResponse
{
    $user = $request->user();

    // Pastikan lesson ini bisa diakses user (ada di course milik package yang ia enroll)
    $accessible = Lesson::active()
        ->where('id', $lesson->id)
        ->whereHas('course.packages', fn ($q) =>
            $q->whereIn('packages.id', $user->enrollments()->pluck('package_id')))
        ->exists();

    if (! $accessible) {
        abort(403, 'Pelajaran ini tidak dapat diakses.');
    }

    LessonProgress::updateOrCreate(
        ['user_id' => $user->id, 'lesson_id' => $lesson->id],
        ['is_completed' => true, 'completed_at' => now()],
    );

    return response()->json([
        'success' => true,
        'message' => 'Pelajaran ditandai selesai.',
        'data' => [
            'id'        => $lesson->id,
            'completed' => true,
        ],
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
