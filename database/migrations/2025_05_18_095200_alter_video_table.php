<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterVideoTable extends Migration
{
    public function up()
    {
        Schema::table('video', function (Blueprint $table) {
            $table->dropColumn(['segment_urls', 'order']);
        });
    }

    public function down()
    {
        Schema::table('video', function (Blueprint $table) {
            $table->longText('segment_urls')->nullable();
            $table->unsignedInteger('order')->default(0);
        });
    }
}