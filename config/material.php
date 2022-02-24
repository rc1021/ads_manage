<?php

return [

    // 是否強制使用 ssl
    'https' => env('MATERIAL_HTTPS', strpos(env('APP_URL', ''), 'https://') === 0),

    'route' => [

        'prefix' => env('MATERIAL_ROUTE_PREFIX', 'material'),

        'namespace' => 'TargetConvert\\Material\\Controllers',

        'middleware' => [],

    ],

    'database' => [

        // 是否啟用資料表動態prefix（來自 session)
        'enable_dynamic_table_prefix' => true,

        // 資料表 prefix (只有 `enable_dynamic_table_prefix` 為 false 時有效)
        'table_prefix' => env('MATERIAL_DATABASE_PREFIX', ''),

        // Image tables and model.
        'image_table' => 'material_images',
        'image_model' => TargetConvert\Material\Models\Image::class,

        // Video tables and model.
        'video_table' => 'material_videos',
        'video_model' => TargetConvert\Material\Models\Video::class,

        // Url tables and model.
        'url_table' => 'material_urls',
        'url_model' => TargetConvert\Material\Models\Url::class,

        // Material tables and model.
        'material_table' => 'materials',
        'material_model' => TargetConvert\Material\Models\Material::class,

        // MaterialTag tables and model.
        'material_tag_table' => 'material_tags',
        'material_tag_model' => TargetConvert\Material\Models\MaterialTag::class,

        // MaterialTagFolder tables and model.
        'material_tag_folder_table' => 'material_tag_folders',
        'material_tag_folder_model' => TargetConvert\Material\Models\MaterialTagFolder::class,

        // MaterialTagMaterial tables and model.
        'material_tag_material_table' => 'material_tag_materials',
        'material_tag_material_model' => TargetConvert\Material\Models\MaterialTagMaterial::class,

    ],

];
