<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaterialTagMaterialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('material_tag_materials', function (Blueprint $table) {
            $table->foreignId('material_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('material_tag_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
            $table->index(['material_id', 'material_tag_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('material_tag_materials');
    }
}
