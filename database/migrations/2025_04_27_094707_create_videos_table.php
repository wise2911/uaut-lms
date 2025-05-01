<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVideosTable extends Migration
{
    public function up()
    {
        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('cloudinary_url');
            $table->unsignedInteger('topic_index'); // Index of the topic in the course's topics array
            $table->unsignedInteger('order')->default(0); // Order within the topic
            $table->unsignedInteger('duration')->nullable(); // Duration in seconds
            $table->json('segments')->nullable(); // JSON array of segments [{start: 0, end: 1200}, ...]
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('videos');
    }
}