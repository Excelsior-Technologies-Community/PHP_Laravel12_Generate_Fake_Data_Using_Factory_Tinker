<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;

Route::get('/posts', [PostController::class, 'index'])->name('posts.index');

Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');

Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');