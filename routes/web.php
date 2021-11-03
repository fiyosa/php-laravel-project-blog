<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
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

Route::get('/', [HomeController::class, 'index']);
Route::get('/about', [AboutController::class, 'index']);

Route::get('/blog', [BlogController::class, 'index']);
Route::get('/blog/{slug}', [BlogController::class, 'show']);

Route::get('/register', [RegisterController::class, 'index']);
Route::post('/register', [RegisterController::class, 'store']);

Route::get('/login', [LoginController::class, 'index']);
Route::post('/login', [LoginController::class, 'authenticate']);

Route::get('/dashboard', [AdminController::class, 'index']);
Route::get('/dashboard/posts', [AdminController::class, 'posts']);
Route::get('/dashboard/posts/create', [AdminController::class, 'create_show']);
Route::get('/dashboard/posts/show/{slug}', [AdminController::class, 'show']);
Route::get('/dashboard/posts/edit/{slug}', [AdminController::class, 'edit_show']);

Route::post('/dashboard/posts/create', [AdminController::class, 'create']);
Route::post('/dashboard/posts/edit/{slug}', [AdminController::class, 'edit']);
Route::post('/dashboard/posts/delete/{slug}', [AdminController::class, 'delete']);





