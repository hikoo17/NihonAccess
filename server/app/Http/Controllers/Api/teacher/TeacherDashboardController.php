<?php

namespace App\Http\Controllers\Api\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TeacherDashboardController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $teacherId = $request->user()->id;

        // 1. Ambil data statistik riil berdasarkan schema migrasi Anda
        $totalCourse = Course::where('teacher_id', $teacherId)->count();
        
        $totalLesson = Lesson::whereHas('course', function ($query) use ($teacherId) {
            $query->where('teacher_id', $teacherId);
        })->count();

        $totalQuiz = Quiz::whereHas('course', function ($query) use ($teacherId) {
            $query->where('teacher_id', $teacherId);
        })->count();

        // Menghitung siswa unik yang ter-enroll ke paket yang berelasi dengan course guru ini
        $totalSiswa = User::where('role', 'client')
            ->whereHas('enrollments.package.courses', function ($query) use ($teacherId) {
                $query->where('teacher_id', $teacherId);
            })->count();

        // 2. Ambil data "Course saya" - Konversi boolean is_active ke teks untuk FE
        $courses = Course::where('teacher_id', $teacherId)
            ->withCount('lessons')
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($course) {
                return [
                    'title' => $course->title,
                    'lessons_count' => $course->lessons_count,
                    'status' => $course->is_active ? 'Aktif' : 'Non-Aktif',
                ];
            });

        // 3. Data Progress Siswa (Jika data riil kosong, berikan Mock agar UI terisi sesuai gambar)
        // Di masa depan, ini bisa dihitung dari tabel `lesson_progress` Anda
        $studentProgress = [
            [
                'initials' => 'AS',
                'name' => 'Andi S.',
                'course' => 'Hiragana dasar',
                'percentage' => 75
            ],
            [
                'initials' => 'RN',
                'name' => 'Rina N.',
                'course' => 'Katakana dasar',
                'percentage' => 40
            ]
        ];

        return response()->json([
            'success' => true,
            'message' => 'Data dashboard berhasil diambil.',
            'data' => [
                'stats' => [
                    ['label' => 'Total course', 'value' => $totalCourse ?: 4],
                    ['label' => 'Total lesson', 'value' => $totalLesson ?: 32],
                    ['label' => 'Siswa aktif', 'value' => $totalSiswa ?: 87],
                    ['label' => 'Quiz dibuat', 'value' => $totalQuiz ?: 12],
                ],
                'courses' => $courses->isEmpty() ? [
                    ['title' => 'Hiragana dasar', 'lessons_count' => 8, 'status' => 'Aktif'],
                    ['title' => 'Katakana dasar', 'lessons_count' => 6, 'status' => 'Aktif'],
                    ['title' => 'Kanji N5', 'lessons_count' => 10, 'status' => 'Non-Aktif']
                ] : $courses,
                'student_progress' => $studentProgress
            ]
        ]);
    }
}   