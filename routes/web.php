<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\FactoryStudioController;

Route::get('/', function () {
    return redirect()->route('posts.index');
});

// Posts Resource & Filtering
Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');
Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');

// Export Suite
Route::get('/posts/export/csv', [PostController::class, 'exportCsv'])->name('posts.export.csv');
Route::get('/posts/export/json', [PostController::class, 'exportJson'])->name('posts.export.json');
Route::get('/posts/export/print', [FactoryStudioController::class, 'exportPrint'])->name('posts.export.print');

// 1-Click Interactive Factory Studio
Route::post('/posts/generate', [FactoryStudioController::class, 'generate'])->name('posts.generate');

// Web Tinker Console REPL
Route::post('/posts/tinker', [FactoryStudioController::class, 'tinker'])->name('posts.tinker');

// Sandbox Database Reset & Purge
Route::post('/posts/reset', [FactoryStudioController::class, 'reset'])->name('posts.reset');
Route::post('/posts/purge', [FactoryStudioController::class, 'purge'])->name('posts.purge');

// Live Chart.js Analytics API
Route::get('/posts/api/analytics', [FactoryStudioController::class, 'analytics'])->name('posts.analytics');