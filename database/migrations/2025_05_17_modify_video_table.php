<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('video', function (Blueprint $table) {
            $table->dropColumn(['topic_index', 'segments']);
            $table->json('segment_urls')->nullable()->after('url');
        });
    }

    public function down(): void
    {
        Schema::table('video', function (Blueprint $table) {
            $table->unsignedInteger('topic_index');
            $table->longText('segments')->nullable();
            $table->dropColumn('segment_urls');
        });
    }
};