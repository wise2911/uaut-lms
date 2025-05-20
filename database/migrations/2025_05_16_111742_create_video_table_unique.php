<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVideoTableUnique extends Migration
{
    public function up()
    {
        Schema::create('video', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('url'); // Stores path like videos/preview.mp4
            $table->unsignedInteger('topic_index'); // Index of the topic in the course's topics array
            $table->unsignedInteger('order')->default(0); // Order within the topic
            $table->boolean('is_preview')->default(false); // Indicates if the video is a preview
            $table->json('segments')->nullable(); // JSON array of segments [{start: 0, end: 1200}, ...]
            $table->timestamps();
        });

        Schema::create('user_video', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('video_id')->constrained('video')->onDelete('cascade');
            $table->unsignedInteger('progress')->default(0); // Progress percentage (0-100)
            $table->timestamp('completed_at')->nullable(); // When the video was fully completed
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_video');
        Schema::dropIfExists('video');
    }
}