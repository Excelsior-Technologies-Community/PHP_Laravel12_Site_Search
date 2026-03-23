<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class PostController extends Controller
{
   public function show(Request $request, $id)
{
    $post = Post::findOrFail($id);
    $query = $request->input('query'); // preserve search query
    return view('posts.show', compact('post', 'query'));
}
}