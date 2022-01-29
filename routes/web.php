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

Route::get('/', function () {
    return view('welcome');
});

Route::post('materials/upload', MaterialController::class."@upload")->name('materials.upload');
Route::resource('materials', MaterialController::class)->only(['index', 'store', 'update', 'show']);
Route::resource('material_tags', MaterialTagController::class)->only(['store', 'update', 'destroy']);
