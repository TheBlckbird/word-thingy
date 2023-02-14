<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\WordController;

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

Route::post('page/auth/login', [AuthController::class, 'login']);
Route::get('page/{id}/logout', [AuthController::class, 'logout']);
Route::post('word/{pageId}/ban', [WordController::class, 'banWord']);
Route::post('word/{pageId}/unban', [WordController::class, 'unbanWord']);
Route::post('word/{pageId}/new', [WordController::class, 'newWord']);
Route::resource('page', PageController::class);

Route::get('/', function() {
    return redirect()->route('page.index');
});