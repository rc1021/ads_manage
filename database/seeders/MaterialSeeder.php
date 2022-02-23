<?php

namespace Database\Seeders;

use App\Enums\MaterialStatusType;
use App\Models\Image;
use App\Models\Material;
use App\Models\MaterialTag;
use App\Models\MaterialTagFolder;
use App\Models\MaterialTagMaterial;
use App\Models\Video;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Arr;


class MaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('material_tag_materials')->truncate();
        DB::table('material_tags')->truncate();
        DB::table('material_tag_folders')->truncate();
        DB::table('materials')->truncate();
        DB::table('images')->truncate();
        DB::table('videos')->truncate();
        DB::table('urls')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        // MaterialTagFolder::insert([
        //     ['id' => 1, 'name' => '常態活動'],
        //     ['id' => 2, 'name' => '短期活動'],
        //     ['id' => 3, 'name' => '家飾寢具'],
        // ]);
        // MaterialTag::insert([
        //     ['id' => 1, 'name' => '常態活動', 'folder_id' => 0],
        //     ['id' => 2, 'name' => '短期活動', 'folder_id' => 0],
        //     ['id' => 3, 'name' => '家飾寢具', 'folder_id' => 0],
        //     ['id' => 4, 'name' => '常態A', 'folder_id' => 1],
        //     ['id' => 5, 'name' => '常態B', 'folder_id' => 1],
        //     ['id' => 6, 'name' => '常態C', 'folder_id' => 1],
        //     ['id' => 7, 'name' => '卡通床包85折', 'folder_id' => 2],
        //     ['id' => 8, 'name' => '床墊新品', 'folder_id' => 2],
        //     ['id' => 9, 'name' => '冬季棉被', 'folder_id' => 2],
        //     ['id' => 10, 'name' => '精梳棉床包', 'folder_id' => 3],
        // ]);
    }
}
