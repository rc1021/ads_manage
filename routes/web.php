<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Enums\TemporaryStatusType;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\MaterialTagController;
use App\Http\Controllers\VideoController;
use App\Models\Material;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

Route::get('/', function () {
    return view('welcome');
});

Route::post('snowflake', function (Request $request) {
    $key = null;
    while(is_null($key) || Cache::has($key)) {
        $key = app('snowflake')->id();
    }
    Cache::put($key, array_merge([
        'status' => TemporaryStatusType::NotYet
    ], $request->all()));
    return response()->json([
        'key' => $key,
        'payload' => Cache::get($key),
    ]);
})->name('snowflake.store');

Route::post('materials/upload', MaterialController::class."@upload")->name('materials.upload');
Route::put('materials/restore/{material}', MaterialController::class."@restore")->withTrashed()->name('materials.restore');
Route::resource('materials', MaterialController::class)->only(['index', 'store', 'update', 'show', 'destroy']);
Route::resource('material_tags', MaterialTagController::class)->only(['store', 'update', 'destroy']);
Route::post('material_tag_folders', MaterialTagController::class."@fstore")->name('material_tag_folders.store');
Route::put('material_tag_folders/{material_tag_folder}', MaterialTagController::class."@fupdate")->name('material_tag_folders.update');
Route::delete('material_tag_folders/{material_tag_folder}', MaterialTagController::class."@fdelete")->name('material_tag_folders.destroy');

Route::get('videos/redo/{video}', VideoController::class.'@redo')->name('videos.redo');
Route::get('videos/secret/{key}', function ($key) { return Storage::disk(config('filesystems.default'))->download(Material::DirectorySecret . $key); })->name('videos.secret');
Route::get('videos/playlist/{pathinfo}', VideoController::class.'@playlist')->where('pathinfo', '.*')->name('videos.playlist');
Route::resource('videos', VideoController::class)->only(['index', 'store', 'show']);
