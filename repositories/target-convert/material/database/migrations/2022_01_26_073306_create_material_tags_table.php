<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaterialTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('material_tags', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('folder_id')->default(0);
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['folder_id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('material_tags');
    }
}
