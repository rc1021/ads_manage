<?php

use App\Enums\MaterialType;
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
            $table->id();
            $table->enum('type', MaterialType::getValues())->default(MaterialType::Text);
            $table->longText('title')->unique();
            $table->bigInteger('user_id')->default(0);
            $table->timestamps();
            $table->softDeletes();
            $table->json('extra_data')->nullable();

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
