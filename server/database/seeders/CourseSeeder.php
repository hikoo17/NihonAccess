<?php

namespace Database\Seeders;

use App\Models\CharacterExercise;
use App\Models\Course;
use App\Models\ListeningExercise;
use App\Models\Package;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\User;
use App\Models\WritingExercise;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $teacher = User::where('role', 'teacher')->first();
        $teacherId = $teacher?->id;

        $courses = [
            [
                'title' => 'Hiragana & Katakana Dasar',
                'slug' => 'hiragana-katakana-dasar',
                'description' => 'Pelajari 46 huruf hiragana dan 46 huruf katakana dari nol.',
                'level' => 'Pemula',
                'packages' => ['basic', 'premium', 'private', 'reguler', 'intensive'],
                'lessons' => [
                    ['title' => 'Pengenalan Hiragana', 'slug' => 'pengenalan-hiragana', 'content' => 'Mengenal 46 huruf hiragana dasar.'],
                    ['title' => 'Pengenalan Katakana', 'slug' => 'pengenalan-katakana', 'content' => 'Mengenal 46 huruf katakana dasar.'],
                    ['title' => 'Kosakata Dasar', 'slug' => 'kosakata-dasar', 'content' => '300+ kata umum bahasa Jepang.'],
                ],
                'quizzes' => [
                    [
                        'title' => 'Kuis Hiragana',
                        'description' => 'Uji kemampuan membaca huruf hiragana.',
                        'passing_score' => 70,
                        'questions' => [
                            ['question' => 'Huruf hiragana untuk "a" adalah?', 'options' => ['あ', 'い', 'う', 'え'], 'correct_answer' => 'あ'],
                            ['question' => 'Huruf hiragana untuk "ka" adalah?', 'options' => ['か', 'き', 'く', 'け'], 'correct_answer' => 'か'],
                            ['question' => 'Huruf hiragana untuk "sa" adalah?', 'options' => ['さ', 'し', 'す', 'せ'], 'correct_answer' => 'さ'],
                        ],
                    ],
                ],
                'listening' => [
                    ['title' => 'Listening Hiragana', 'description' => 'Dengarkan dan tebak huruf.', 'audio_url' => '/audio/hiragana-1.mp3'],
                ],
                'characters' => [
                    ['character_type' => 'hiragana', 'character' => 'あ', 'answer' => 'a'],
                    ['character_type' => 'hiragana', 'character' => 'い', 'answer' => 'i'],
                    ['character_type' => 'hiragana', 'character' => 'う', 'answer' => 'u'],
                    ['character_type' => 'katakana', 'character' => 'ア', 'answer' => 'a'],
                    ['character_type' => 'katakana', 'character' => 'イ', 'answer' => 'i'],
                ],
                'writing' => [
                    ['character_type' => 'hiragana', 'character' => 'あ', 'stroke_order_image' => '/img/stroke/hiragana-a.png'],
                    ['character_type' => 'hiragana', 'character' => 'い', 'stroke_order_image' => '/img/stroke/hiragana-i.png'],
                ],
            ],
            [
                'title' => 'Grammar JLPT N5',
                'slug' => 'grammar-jlpt-n5',
                'description' => 'Pola kalimat dasar untuk persiapan JLPT N5.',
                'level' => 'N5',
                'packages' => ['premium', 'private', 'intensive'],
                'lessons' => [
                    ['title' => 'Pola Kalimat Dasar', 'slug' => 'pola-kalimat-dasar', 'content' => 'Subjek + objek + kata kerja (SOV).'],
                    ['title' => 'Partikel wa & ga', 'slug' => 'partikel-wa-ga', 'content' => 'Penggunaan partikel は dan が.'],
                    ['title' => 'Bentuk Lampau', 'slug' => 'bentuk-lampau', 'content' => 'Menggunakan bentuk lampau ~た.'],
                ],
                'quizzes' => [
                    [
                        'title' => 'Kuis Grammar N5',
                        'description' => 'Uji pemahaman grammar JLPT N5.',
                        'passing_score' => 70,
                        'questions' => [
                            ['question' => 'Partikel untuk topik kalimat adalah?', 'options' => ['は', 'が', 'を', 'に'], 'correct_answer' => 'は'],
                            ['question' => 'Bentuk lampau dari 食べる adalah?', 'options' => ['食べた', '食べます', '食べない', '食べる'], 'correct_answer' => '食べた'],
                        ],
                    ],
                ],
                'listening' => [
                    ['title' => 'Listening Grammar N5', 'description' => 'Dengarkan kalimat sederhana.', 'audio_url' => '/audio/grammar-n5-1.mp3'],
                ],
                'characters' => [
                    ['character_type' => 'kanji', 'character' => '日', 'answer' => 'hi/nichi'],
                    ['character_type' => 'kanji', 'character' => '本', 'answer' => 'hon'],
                ],
                'writing' => [
                    ['character_type' => 'kanji', 'character' => '日', 'stroke_order_image' => '/img/stroke/kanji-hi.png'],
                ],
            ],
            [
                'title' => 'Kanji Dasar',
                'slug' => 'kanji-dasar',
                'description' => '200+ kanji dasar yang sering muncul dalam JLPT N5.',
                'level' => 'N5',
                'packages' => ['premium', 'private', 'intensive'],
                'lessons' => [
                    ['title' => 'Kanji Angka', 'slug' => 'kanji-angka', 'content' => '一二三四五六七八九十.'],
                    ['title' => 'Kanji Waktu', 'slug' => 'kanji-waktu', 'content' => '日月年時分.'],
                ],
                'quizzes' => [
                    [
                        'title' => 'Kuis Kanji',
                        'description' => 'Tebak bacaan kanji.',
                        'passing_score' => 70,
                        'questions' => [
                            ['question' => 'Kanji untuk angka "satu" adalah?', 'options' => ['一', '二', '三', '四'], 'correct_answer' => '一'],
                            ['question' => 'Kanji untuk "hari" adalah?', 'options' => ['日', '月', '年', '時'], 'correct_answer' => '日'],
                        ],
                    ],
                ],
                'listening' => [],
                'characters' => [
                    ['character_type' => 'kanji', 'character' => '一', 'answer' => 'ichi'],
                    ['character_type' => 'kanji', 'character' => '二', 'answer' => 'ni'],
                    ['character_type' => 'kanji', 'character' => '三', 'answer' => 'san'],
                ],
                'writing' => [
                    ['character_type' => 'kanji', 'character' => '一', 'stroke_order_image' => '/img/stroke/kanji-ichi.png'],
                ],
            ],
        ];

        foreach ($courses as $data) {
            $lessons = $data['lessons'];
            $quizzes = $data['quizzes'] ?? [];
            $listening = $data['listening'] ?? [];
            $characters = $data['characters'] ?? [];
            $writing = $data['writing'] ?? [];
            $packageSlugs = $data['packages'] ?? [];
            unset($data['lessons'], $data['quizzes'], $data['listening'], $data['characters'], $data['writing'], $data['packages']);

            $course = Course::create(array_merge($data, ['is_active' => true, 'teacher_id' => $teacherId]));

            $packageIds = Package::whereIn('slug', $packageSlugs)->pluck('id');
            $course->packages()->attach($packageIds);

            foreach ($lessons as $index => $lesson) {
                $course->lessons()->create([
                    'title' => $lesson['title'],
                    'slug' => $lesson['slug'],
                    'content' => $lesson['content'] ?? null,
                    'sort_order' => $index,
                    'is_active' => true,
                ]);
            }

            foreach ($quizzes as $quizData) {
                $questions = $quizData['questions'] ?? [];
                unset($quizData['questions']);

                $quiz = $course->quizzes()->create(array_merge($quizData, ['is_active' => true]));

                foreach ($questions as $index => $q) {
                    $quiz->questions()->create([
                        'question' => $q['question'],
                        'options' => $q['options'],
                        'correct_answer' => $q['correct_answer'],
                        'explanation' => $q['explanation'] ?? null,
                        'sort_order' => $index,
                    ]);
                }
            }

            foreach ($listening as $item) {
                $course->listeningExercises()->create(array_merge($item, ['is_active' => true]));
            }

            foreach ($characters as $item) {
                CharacterExercise::create(array_merge($item, ['course_id' => $course->id, 'is_active' => true]));
            }

            foreach ($writing as $item) {
                WritingExercise::create(array_merge($item, ['course_id' => $course->id, 'is_active' => true]));
            }
        }
    }
}
