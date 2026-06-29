<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->enum('type', ['online', 'onsite'])->default('online')->after('slug');
            $table->string('icon')->nullable()->after('type');
            $table->string('badge')->nullable()->after('icon');
            $table->string('format')->nullable()->after('badge');
            $table->string('level')->nullable()->after('format');
            $table->string('price_note')->nullable()->after('price');
            $table->json('highlights')->nullable()->after('description');
            $table->json('modules')->nullable()->after('highlights');
            $table->json('suitable_for')->nullable()->after('modules');
        });
    }

    public function down(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn([
                'type', 'icon', 'badge', 'format', 'level',
                'price_note', 'highlights', 'modules', 'suitable_for',
            ]);
        });
    }
};
