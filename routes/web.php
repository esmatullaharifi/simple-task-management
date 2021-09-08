<?php

use App\Http\Controllers\{ProjectController, TaskController};
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
    return view('welcome');
});

Route::group(['middleware' => 'auth'], function () {
    Route::resource('projects', ProjectController::class)->except('show');
    Route::resource('tasks', TaskController::class)->except('show');
    Route::post('change-priorities', [TaskController::class, 'changePriorities'])->name('tasks.changePriorities');
});

require __DIR__.'/auth.php';
