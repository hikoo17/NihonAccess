<?php

namespace App\Http\Controllers\Api\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\LessonProgress;
use App\Models\Quiz;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeacherDashboardController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $teacherId = $request->user()->id;

        $totalCourse = Course::where('teacher_id', $teacherId)->count();

        $totalLesson = Lesson::whereHas('course', fn ($q) => $q->where('teacher_id', $teacherId))->count();

        $totalQuiz = Quiz::whereHas('course', fn ($q) => $q->where('teacher_id', $teacherId))->count();

        $totalSiswa = DB::table('users')
            ->where('users.role', 'client')
            ->join('enrollments', 'enrollments.user_id', '=', 'users.id')
            ->join('course_package', 'course_package.package_id', '=', 'enrollments.package_id')
            ->join('courses', 'courses.id', '=', 'course_package.course_id')
            ->where('courses.teacher_id', $teacherId)
            ->distinct()
            ->count('users.id');

        $courses = Course::where('teacher_id', $teacherId)
            ->withCount('lessons')
            ->latest()
            ->take(5)
            ->get()
            ->map(fn ($c) => [
                'id'            => $c->id,
                'title'         => $c->title,
                'lessons_count' => $c->lessons_count,
                'is_active'     => $c->is_active,
            ]);

        // Progress siswa dari DB, bukan hardcode
        $studentProgress = LessonProgress::query()
            ->with(['user:id,name', 'lesson.course:id,title,teacher_id'])
            ->whereHas('lesson.course', fn ($q) => $q->where('teacher_id', $teacherId))
            ->latest('updated_at')
            ->take(5)
            ->get()
            ->map(fn ($lp) => [
                'name'       => $lp->user?->name ?? '-',
                'course'     => $lp->lesson?->course?->title ?? '-',
                'percentage' => $lp->is_completed ? 100 : 0,
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Data dashboard berhasil diambil.',
            'data'    => [
                'stats' => [
                    ['label' => 'Total course',  'value' => $totalCourse],
                    ['label' => 'Total lesson',  'value' => $totalLesson],
                    ['label' => 'Siswa aktif',   'value' => $totalSiswa],
                    ['label' => 'Quiz dibuat',   'value' => $totalQuiz],
                ],
                'courses'          => $courses,
                'student_progress' => $studentProgress,
            ],
        ]);
    }
}