<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PostController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SuggestionController;


/*
|--------------------------------------------------------------------------
| Home Route
|--------------------------------------------------------------------------
|
| Displays the search homepage
|
*/
Route::get('/', function () {
    return view('welcome');
});


/*
|--------------------------------------------------------------------------
| Search Routes
|--------------------------------------------------------------------------
|
| Handles search results and live suggestions
|
*/
Route::get('/search', [SearchController::class, 'index'])
        ->name('search');

Route::get('/suggestions', [SuggestionController::class, 'index'])
        ->name('suggestions');


/*
|--------------------------------------------------------------------------
| Post Routes
|--------------------------------------------------------------------------
|
| Displays individual post details
|
*/
Route::get('/posts/{id}', [PostController::class, 'show'])
        ->name('posts.show');