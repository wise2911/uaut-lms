<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn(['topics', 'thumbnail_url']);
            $table->longText('learning_outcomes')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->longText('topics');
            $table->string('thumbnail_url')->nullable();
            $table->longText('learning_outcomes')->nullable(false)->change();
        });
    }
};