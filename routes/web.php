<?php

use App\Http\Controllers\LikeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\ReplyController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UserController;
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



Auth::routes();

Route::resource('question', QuestionController::class);
Route::resource('tag', TagController::class);
Route::resource('question.reply', ReplyController::class)->shallow();
Route::resource('reply.like', LikeController::class)->shallow();
Route::resource('user', UserController::class);
Route::resource('profile', ProfileController::class);

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
