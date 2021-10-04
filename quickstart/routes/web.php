<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\MainController;
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

Route::get('/', [MainController::class, 'index'])->name('index');

Route::post('/store', [MainController::class, 'store'])->name('linkStore');
Route::get('/all/{link}', [MainController::class, 'showAllResults'])->name('allResults');

Route::post('/results', [MainController::class, 'makeRequests'])->name('makeRequests');
