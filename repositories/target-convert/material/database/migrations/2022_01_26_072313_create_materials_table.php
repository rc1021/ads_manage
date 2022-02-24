<?php

use TargetConvert\Material\Enums\MaterialStatusType;
use TargetConvert\Material\Enums\MaterialType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaterialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('materials', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('type', MaterialType::getValues());
            $table->enum('status_type', MaterialStatusType::getValues());
            $table->longText('title')->unique();
            $table->bigInteger('user_id')->default(0);
            $table->timestamps();
            $table->softDeletes();
            $table->bigInteger('mediaable_id')->nullable();
            $table->string('mediaable_type')->nullable();

            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('materials');
    }
}
