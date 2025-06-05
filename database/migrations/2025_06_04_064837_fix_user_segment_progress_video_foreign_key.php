<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixUserSegmentProgressVideoForeignKey extends Migration
{
    public function up()
    {
        Schema::table('user_segment_progress', function (Blueprint $table) {
            // Drop existing foreign key
            $table->dropForeign(['video_id']);
            
            // Add new foreign key referencing 'video' table
            $table->foreign('video_id')
                  ->references('id')
                  ->on('video')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('user_segment_progress', function (Blueprint $table) {
            // Drop new foreign key
            $table->dropForeign(['video_id']);
            
            // Restore old foreign key (if needed)
            $table->foreign('video_id')
                  ->references('id')
                  ->on('videos')
                  ->onDelete('cascade');
        });
    }
}