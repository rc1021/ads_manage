<?php

namespace TargetConvert\Material;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;
use TargetConvert\Material\Enums\TemporaryStatusType;
use TargetConvert\Material\Models\Material as ModelsMaterial;

class Material
{
    /**
     * 註冊素材庫的路由
     *
     * @return void
     */
    public function routes()
    {
        $attributes = [
            'prefix'     => config('material.route.prefix'),
            'middleware' => config('material.route.middleware'),
        ];

        app('router')->group($attributes, function ($router) {

            /* @var \Illuminate\Support\Facades\Route $router */
            $router->name(config('material.route.prefix') . '.')
                   ->namespace('\TargetConvert\Material')
                   ->group(function ($router) {


                if(config('app.debug')) {
                    $router->get('/init', function () {
                        Artisan::call('modelCache:clear');
                        Artisan::call('cache:clear');
                        Artisan::call('route:clear');
                        Artisan::call('view:clear');
                        Artisan::call('material:install', ['--force' => true]);
                        material_info(__('系統已重置'));
                        return redirect()->route('material.items.index');
                    })->name('init');
                }

                $router->get('/', function () {
                    return redirect()->route(config('material.route.prefix').'.items.index');
                })->name('index');

                // 唯一 ID 產生器
                $router->post('snowflake', "MaterialController@snowflake")->name('snowflake.store');

                // folders
                $router->post('tag/folders', "MaterialController@folder_store")->name('tag.folders.store');
                $router->put('tag/folders/{folder}', "MaterialController@folder_update")->name('tag.folders.update');
                $router->delete('tag/folders/{folder}', "MaterialController@folder_destroy")->name('tag.folders.destroy');

                // tas
                $router->post('tags', "MaterialController@tag_store")->name('tags.store');
                $router->put('tags/{tag}', "MaterialController@tag_update")->name('tags.update');
                $router->delete('tags/{tag}', "MaterialController@tag_destroy")->name('tags.destroy');

                // items
                $router->post('items/upload', "MaterialController@item_upload")->name('items.upload');
                $router->put('items/restore/{item}', function ($item) {
                    return $item;
                })->name('items.restore');
                $router->get('items', "MaterialController@item_index")->name('items.index');
                $router->post('items', "MaterialController@item_store")->name('items.store');
                $router->put('items/{item}', "MaterialController@item_update")->name('items.update');
                $router->delete('items/{item}', "MaterialController@item_destroy")->name('items.destroy');

                // $router->get('videos/redo/{video}', 'VideoController@redo')->name('videos.redo');
                // $router->get('videos/secret/{key}', function ($key) { return Storage::disk(config('filesystems.default'))->download(ModelsMaterial::DirectorySecret . $key); })->name('videos.secret');
                // $router->get('videos/playlist/{pathinfo}', 'VideoController@playlist')->where('pathinfo', '.*')->name('videos.playlist');
                // $router->resource('videos', 'VideoController')->only(['index', 'store', 'show']);
            });
        });
    }
}
