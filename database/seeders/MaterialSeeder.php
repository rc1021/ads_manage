<?php

namespace Database\Seeders;

use App\Enums\MaterialStatusType;
use App\Models\Image;
use App\Models\Material;
use App\Models\MaterialTag;
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
        DB::table('materials')->truncate();
        DB::table('images')->truncate();
        DB::table('videos')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        MaterialTag::insert([
            ['id' => 1, 'name' => '未命名', 'parent_id' => 0, 'drop' => false],
            ['id' => 2, 'name' => '米森 vilson', 'parent_id' => 1, 'drop' => true],
            ['id' => 3, 'name' => '食品', 'parent_id' => 1, 'drop' => true],
            ['id' => 4, 'name' => 'Vilson Park X KOMAX', 'parent_id' => 1, 'drop' => true],
            ['id' => 5, 'name' => 'Vilson Park', 'parent_id' => 1, 'drop' => true],
        ]);
        $materials = [
            ['tag' => [2, 3], 'type' => '1', 'status_type' => '4', 'title' => '【米森 vilson】玫瑰鹽烤-有機什錦纖果(160g/罐)'],
            ['tag' => [2, 3], 'type' => '1', 'status_type' => '4', 'title' => '【米森 vilson】乳酸菌咬咬蜂蜜優格(10gx5包/袋)'],
            ['tag' => [2, 3], 'type' => '1', 'status_type' => '4', 'title' => '【米森 vilson】益生菌優格粉1盒(2gx5包)+鑄鐵鍋折折盒900ML乙個(顏色隨機出貨)【免運】'],
            ['tag' => [2, 3], 'type' => '1', 'status_type' => '4', 'title' => '【米森 vilson】有機漢方紅棗枸杞茶(6gx8包/盒)'],
            ['tag' => [2, 3], 'type' => '1', 'status_type' => '4', 'title' => '【米森 vilson】益生菌優格粉2盒(2gx5包/盒)+BC益生菌脆麥片1盒(口味可選)(300g)+鑄鐵鍋折折盒900ML乙個(顏色隨機出貨)【免運】★'],
            ['tag' => [2, 3], 'type' => '1', 'status_type' => '4', 'title' => '【米森 vilson】益生菌優格粉1盒(2gx5包)+日本水切優格盒1個+鑄鐵鍋折折盒900ML乙個(顏色隨機出貨)【免運】'],
            ['tag' => [2, 3], 'type' => '1', 'status_type' => '4', 'title' => '【米森 vilson】益生菌優格粉2盒(2gx5包/盒)+鑄鐵鍋折折盒900ML乙個(顏色隨機出貨)【免運】'],
            ['tag' => [2, 3], 'type' => '1', 'status_type' => '4', 'title' => '【米森 vilson】益生菌優格粉1盒(2gx5包)+水果麥片隨手包5包(50g/包)+鑄鐵鍋折折盒900ML乙個(顏色隨機出貨)【免運】'],
            ['tag' => [2, 3], 'type' => '1', 'status_type' => '4', 'title' => '【米森 vilson】有機莓好堅果禮盒-3罐組(460g/盒)【限時優惠↘71折】＜售價已折＞'],
            ['tag' => [2, 3], 'type' => '1', 'status_type' => '4', 'title' => '【米森 vilson】有機活力堅果禮盒-3罐組(450g/盒)【限時優惠↘71折】＜售價已折＞'],
            ['tag' => [2, 3], 'type' => '1', 'status_type' => '4', 'title' => '【米森 vilson】有機好油禮盒(2瓶/組)【2商品口味任選送禮盒】★'],
            ['tag' => [2, 3], 'type' => '1', 'status_type' => '4', 'title' => '【米森 vilson】有機醇濃奶粉(600g/罐)+黑糖老薑茶2盒(20gx8包/盒)【免運】'],
            ['tag' => [2, 3], 'type' => '1', 'status_type' => '4', 'title' => '【米森 vilson】有機醇濃奶粉(600g/罐)+純龍眼蜂蜜(120g/罐)【免運】☆'],
            ['tag' => [2, 3], 'type' => '1', 'status_type' => '4', 'title' => '【米森 vilson】有機醇濃奶粉(600g/罐)+有機國寶茶(口味任選1盒)【免運】★'],
            ['tag' => [2, 3], 'type' => '1', 'status_type' => '4', 'title' => '【米森 vilson】有機醇濃奶粉(600g/罐)+有機即食純杏仁粉(220g/罐)【免運】'],
            ['tag' => [2, 3], 'type' => '1', 'status_type' => '4', 'title' => '【米森 vilson】有機醇濃奶粉(600g/罐)+有機穀物粉(口味任選1罐)【免運】★'],
            ['tag' => [2, 3], 'type' => '1', 'status_type' => '4', 'title' => '【米森 vilson】有機醇濃奶粉 (600g/罐)'],
            ['tag' => [2, 3], 'type' => '1', 'status_type' => '4', 'title' => '【米森 vilson】有機無加糖蔓越莓乾(210g/包)'],
            ['tag' => [2, 3], 'type' => '1', 'status_type' => '4', 'title' => '【米森 vilson】隨手包-香酥草莓穀脆片(20g/包)'],
            ['tag' => [2, 3], 'type' => '1', 'status_type' => '4', 'title' => '【米森 vilson】隨手包-乳酸菌牛奶優格麥片(50g/包)'],
            ['tag' => [2, 3], 'type' => '1', 'status_type' => '4', 'title' => '【米森 vilson】手作優格粉1盒(2gx5包)+乳酸菌優格麥片1盒(300g)【2商品口味可任選－免運】★'],
            ['tag' => [2, 3], 'type' => '1', 'status_type' => '4', 'title' => '【米森 vilson】乳酸菌草莓優格麥片(300g/盒)'],
            ['tag' => [2, 3], 'type' => '1', 'status_type' => '4', 'title' => '【米森 vilson】乳酸菌牛奶優格麥片(300g/盒)'],
            ['tag' => [2, 3], 'type' => '1', 'status_type' => '4', 'title' => '【米森 vilson】GO運動Tritan搖搖水瓶550ml-毅力灰●買就送乳清蛋白粉乙盒(20gx2包)'],
            ['tag' => [2, 3], 'type' => '1', 'status_type' => '4', 'title' => '【米森 vilson】GO運動Tritan搖搖水瓶550ml-專注藍●買就送乳清蛋白粉乙盒(20gx2包)'],
            ['tag' => [2, 3], 'type' => '1', 'status_type' => '4', 'title' => '【米森 vilson】隨手包-BC益生菌堅果脆麥片(40g/包)●有效期限：2022/05/24'],
            ['tag' => [2, 3], 'type' => '1', 'status_type' => '4', 'title' => '【米森 vilson】隨手包-有機無麩質堅果麥片(50g/包)●有效期限：2022/05/19'],
            ['tag' => [2, 3], 'type' => '1', 'status_type' => '4', 'title' => '【米森 vilson】隨手包-有機無麩質野莓麥片(50g/包)●有效期限：2022/05/19'],
            ['tag' => [2, 3], 'type' => '1', 'status_type' => '4', 'title' => '【米森 vilson】BC益生菌草莓脆麥片(300g/盒)+BC益生菌可可脆麥片(300g/盒)+BC益生菌堅果脆麥片(300g/盒)【免運】'],
            ['tag' => [2, 3], 'type' => '1', 'status_type' => '4', 'title' => '【米森 vilson】BC益生菌堅果脆麥片(300g/盒)'],
            ['tag' => [2, 3], 'type' => '1', 'status_type' => '4', 'title' => '【米森 vilson】有機無麩質堅果麥片(400g/盒)'],
            ['tag' => [2, 3], 'type' => '1', 'status_type' => '4', 'title' => '【米森 vilson】隨手包-無調味有機三珍堅果(30g/包)'],
            ['tag' => [5, 3], 'type' => '1', 'status_type' => '4', 'title' => '【Vilson Park X KOMAX】聯名韓製TRITAN水瓶430ml-ZERO狐'],
            ['tag' => [5, 3], 'type' => '1', 'status_type' => '4', 'title' => '【Vilson Park X KOMAX】聯名韓製TRITAN水瓶430ml-MIA兔'],
            ['tag' => [5, 3], 'type' => '1', 'status_type' => '4', 'title' => '【Vilson Park X KOMAX】聯名韓製TRITAN水瓶430ml-ROCK鹿'],
            ['tag' => [5, 3], 'type' => '1', 'status_type' => '4', 'title' => '【Vilson Park X KOMAX】聯名韓製TRITAN水瓶430ml-SLOW熊'],
            ['tag' => [2, 3], 'type' => '1', 'status_type' => '4', 'title' => '【米森 vilson】無加糖分離乳清蛋白飲-國寶拿鐵(35gx6包/盒)'],
            ['tag' => [2, 3], 'type' => '1', 'status_type' => '4', 'title' => '【米森 vilson】無加糖分離乳清蛋白飲-芝麻紫米(35gx6包/盒)'],
            ['tag' => [4, 3], 'type' => '1', 'status_type' => '4', 'title' => '【Vilson park】FUN好物矽膠折疊盒1200ml-玉子黃'],
        ];

        foreach($materials as $material)
        {
            $m = Material::create(Arr::only($material, ['title', 'type', 'status_type']));
            $m->tags()->sync($material['tag']);
        }
    }
}
