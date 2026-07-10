<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PostController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SuggestionController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/search', [SearchController::class, 'index'])
    ->name('search');

Route::get('/suggestions', [SuggestionController::class, 'index'])
    ->name('suggestions');

Route::get('/posts/{id}', [PostController::class, 'show'])
    ->name('posts.show');