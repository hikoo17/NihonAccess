<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Http\Controllers\Api\Teacher\TeacherDashboardController;
use App\Http\Controllers\Api\Teacher\TeacherCourseController;
use App\Http\Controllers\Api\Teacher\TeacherQuizController;
use App\Http\Controllers\Api\Teacher\TeacherCharacterController;
use App\Http\Controllers\Api\Teacher\TeacherListeningController;
use App\Http\Controllers\Api\Teacher\TeacherWritingController;
use App\Models\User;

$teacher = User::where('role', 'teacher')->first();
if (!$teacher) {
    $teacher = User::first();
    echo "No teacher user found, using user #{$teacher->id} (role: {$teacher->role})\n";
} else {
    echo "Using teacher: {$teacher->name} (#{$teacher->id})\n";
}

$req = Illuminate\Http\Request::create('/api/teacher/dashboard', 'GET');
$req->setUserResolver(fn () => $teacher);

$tests = [
    'Dashboard' => fn() => (new TeacherDashboardController())->index($req),
    'Courses'   => fn() => (new TeacherCourseController())->index($req),
    'Quiz'      => fn() => (new TeacherQuizController())->index($req),
    'Character' => fn() => (new TeacherCharacterController())->index($req),
    'Listening' => fn() => (new TeacherListeningController())->index($req),
    'Writing'   => fn() => (new TeacherWritingController())->index($req),
];

foreach ($tests as $name => $fn) {
    try {
        $resp = $fn();
        $data = json_decode($resp->getContent(), true);
        $ok = $data['success'] ?? false;
        $meta = isset($data['meta']) ? " meta=yes" : " meta=no";
        $dkeys = is_array($data['data'] ?? null) ? " data_items=" . count($data['data']) : "";
        echo "$name: HTTP {$resp->getStatusCode()} success=" . ($ok ? 'true' : 'false') . "$meta$dkeys\n";
    } catch (Throwable $e) {
        echo "$name: ERROR - " . $e->getMessage() . "\n";
    }
}