<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaterialVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('material_videos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->string('original_name');
            $table->string('extension');
            $table->string('disk');
            $table->string('path');
            $table->integer('time')->default(0);
            $table->integer('size');
            $table->text('error')->nullable();
            $table->datetime('converted_for_thumbing_at')->nullable();
            $table->datetime('converted_for_downloading_at')->nullable();
            $table->datetime('converted_for_streaming_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('material_videos');
    }
}
