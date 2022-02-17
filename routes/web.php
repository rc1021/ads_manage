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
    App\Models\Image::find(334901028224761856)->material;
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
Route::resource('materials', MaterialController::class)->only(['index', 'store', 'update', 'show']);
Route::resource('material_tags', MaterialTagController::class)->only(['store', 'update', 'destroy']);

Route::get('videos/redo/{video}', VideoController::class.'@redo')->name('videos.redo');
Route::get('videos/secret/{key}', function ($key) { return Storage::disk(config('filesystems.default'))->download(Material::DirectorySecret . $key); })->name('videos.secret');
Route::get('videos/playlist/{pathinfo}', VideoController::class.'@playlist')->where('pathinfo', '.*')->name('videos.playlist');
Route::resource('videos', VideoController::class)->only(['index', 'store', 'show']);
