<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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
    return view('auth/login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/dashboard/dashstats', [App\Http\Controllers\HomeController::class, 'HomeStats'])->name('home.stats');

/*
 * Clients management
 * */
Route::prefix('/clients')->group(function () {
    Route::get('/', [\App\Http\Controllers\ClientsController::class, 'index']);
    Route::get('/{client}', [\App\Http\Controllers\ClientsController::class, 'show']);
    Route::post('/store', [\App\Http\Controllers\ClientsController::class, 'store']);
    Route::patch('/{client}', [\App\Http\Controllers\ClientsController::class, 'update']);
    Route::post('/destroy', [\App\Http\Controllers\ClientsController::class, 'destroyMass']);
    Route::delete('/{client}/destroy', [\App\Http\Controllers\ClientsController::class, 'destroy']);
});

/*
 * Fish temp management
 * */
Route::prefix('/fish_templates')->group(function () {
    Route::get('/', [\App\Http\Controllers\FishController::class, 'index']);
    Route::get('/{fish_template}', [\App\Http\Controllers\FishController::class, 'show']);
    Route::post('/store', [\App\Http\Controllers\FishController::class, 'store']);
    Route::patch('/{FishTemplate}', [\App\Http\Controllers\FishController::class, 'update']);
    Route::post('/destroy', [\App\Http\Controllers\FishController::class, 'destroyMass']);
    Route::delete('/{fish_template}/destroy', [\App\Http\Controllers\FishController::class, 'destroy']);
});

/*
 * Current user
 * */
Route::prefix('/user')->group(function () {
    Route::get('/', [\App\Http\Controllers\CurrentUserController::class, 'show']);
    Route::patch('/', [\App\Http\Controllers\CurrentUserController::class, 'update']);
    Route::patch('/password', [\App\Http\Controllers\CurrentUserController::class, 'updatePassword']);
});

/*
 * File upload (e.g. avatar)
 * */
Route::post('/files/store', [\App\Http\Controllers\FilesController::class, 'store']);
