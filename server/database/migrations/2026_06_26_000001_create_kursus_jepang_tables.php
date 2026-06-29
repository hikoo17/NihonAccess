<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('email')->index();
            $table->string('phone')->nullable();
            $table->enum('status', ['pending', 'active', 'cancelled'])->default('pending')->index();
            $table->timestamp('account_created_at')->nullable();
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('registration_id')
                ->nullable()
                ->after('id')
                ->constrained()
                ->nullOnDelete();
        });

        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->decimal('price', 12, 2);
            $table->unsignedInteger('duration_days');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('package_features', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained()->cascadeOnDelete();
            $table->foreignId('package_id')
                ->constrained()->restrictOnDelete();
            $table->string('order_number')->unique();
            $table->decimal('amount', 12, 2);
            $table->enum('status', [
                'pending',
                'paid',
                'failed',
                'expired',
                'cancelled'
            ])->default('pending')->index();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')
                ->constrained()->cascadeOnDelete();
            $table->string('provider')->default('midtrans');
            $table->string('snap_token')->nullable();
            $table->string('transaction_id')->nullable()->index();
            $table->string('payment_type')->nullable();
            $table->enum('status', [
                'pending',
                'settlement',
                'capture',
                'deny',
                'cancel',
                'expire',
                'failure'
            ])->default('pending')->index();
            $table->json('payload')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });

        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained()->cascadeOnDelete();
            $table->foreignId('order_id')
                ->constrained()->cascadeOnDelete();
            $table->foreignId('package_id')
                ->constrained()->restrictOnDelete();
            $table->date('start_date');
            $table->date('end_date')->index();
            $table->enum('status', ['active', 'expired', 'suspended'])
                ->default('active')->index();
            $table->timestamps();
        });

        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('level')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });

        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('slug');
            $table->longText('content')->nullable();
            $table->string('video_url')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
            $table->unique(['course_id', 'slug']);
        });

        Schema::create('lesson_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('lesson_id')->constrained()->cascadeOnDelete();
            $table->boolean('is_completed')->default(false);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            $table->unique(['user_id', 'lesson_id']);
        });

        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->foreignId('lesson_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->unsignedInteger('passing_score')->default(70);
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });

        Schema::create('quiz_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id')->constrained()->cascadeOnDelete();
            $table->text('question');
            $table->json('options');
            $table->string('correct_answer');
            $table->text('explanation')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('quiz_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('quiz_id')->constrained()->cascadeOnDelete();
            $table->json('answers');
            $table->unsignedInteger('score')->default(0);
            $table->boolean('is_passed')->default(false);
            $table->timestamp('started_at')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
        });

        Schema::create('listening_exercises', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->foreignId('lesson_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('audio_url');
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });

        Schema::create('listening_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('listening_exercise_id')->constrained()->cascadeOnDelete();
            $table->text('question');
            $table->json('options')->nullable();
            $table->string('correct_answer');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('listening_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('listening_exercise_id')->constrained()->cascadeOnDelete();
            $table->json('answers');
            $table->unsignedInteger('score')->default(0);
            $table->boolean('is_passed')->default(false);
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
        });

        Schema::create('character_exercises', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->foreignId('lesson_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('character_type', ['hiragana', 'katakana', 'kanji']);
            $table->string('character');
            $table->string('answer');
            $table->string('hint')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });

        Schema::create('character_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('character_exercise_id')->constrained()->cascadeOnDelete();
            $table->string('answer');
            $table->boolean('is_correct')->default(false);
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
        });

        Schema::create('writing_exercises', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->foreignId('lesson_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('character_type', ['hiragana', 'katakana', 'kanji']);
            $table->string('character');
            $table->string('stroke_order_image')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });

        Schema::create('writing_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('writing_exercise_id')->constrained()->cascadeOnDelete();
            $table->string('submission_image')->nullable();
            $table->json('metadata')->nullable();
            $table->unsignedInteger('score')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
        });
        Schema::create('course_package', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->foreignId('package_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['course_id', 'package_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_package');
        Schema::dropIfExists('writing_attempts');
        Schema::dropIfExists('writing_exercises');
        Schema::dropIfExists('character_attempts');
        Schema::dropIfExists('character_exercises');
        Schema::dropIfExists('listening_attempts');
        Schema::dropIfExists('listening_questions');
        Schema::dropIfExists('listening_exercises');
        Schema::dropIfExists('quiz_attempts');
        Schema::dropIfExists('quiz_questions');
        Schema::dropIfExists('quizzes');
        Schema::dropIfExists('lesson_progress');
        Schema::dropIfExists('lessons');
        Schema::dropIfExists('courses');
        Schema::dropIfExists('enrollments');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('package_features');
        Schema::dropIfExists('packages');

        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('registration_id');
        });

        Schema::dropIfExists('registrations');
    }
};
