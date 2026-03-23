<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\SearchController;

Route::get('/', function () {
    return view('welcome'); // home page with search form
});

Route::get('/search', [SearchController::class, 'index'])->name('search');
Route::get('/posts/{id}', [PostController::class, 'show'])->name('posts.show');
