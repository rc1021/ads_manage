<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\MaterialTagController;

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
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome');
});

Route::post('snowflake', function (Request $request) {
    $key = null;
    while(is_null($key) || Cache::has($key)) {
        $key = app('snowflake')->id();
    }
    Cache::put($key, $request->all());
    return $key;
})->name('snowflake.store');

Route::post('materials/upload', MaterialController::class."@upload")->name('materials.upload');
Route::resource('materials', MaterialController::class)->only(['index', 'store', 'update', 'show']);
Route::resource('material_tags', MaterialTagController::class)->only(['store', 'update', 'destroy']);
