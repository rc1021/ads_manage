<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameParentIdToMaterialTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('material_tags', function (Blueprint $table) {
            $table->dropColumn('drop');
            $table->dropIndex(['parent_id']);
            $table->dropUnique(['name']);
            $table->renameColumn('parent_id', 'folder_id');
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
        Schema::table('material_tags', function (Blueprint $table) {
            $table->dropUnique(['folder_id', 'name']);
            $table->renameColumn('folder_id', 'parent_id');
            $table->unique(['name']);
            $table->index('parent_id');
            $table->boolean('drop')->default(true);
        });
    }
}
