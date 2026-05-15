<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class SuggestionController extends Controller
{
    public function index(Request $request)
    {
        $search=$request->search;

        if(!$search)
        {
            return response()->json([]);
        }

        $posts=Post::where(
                    'title',
                    'like',
                    "%{$search}%"
                )
                ->limit(5)
                ->pluck('title');

        return response()->json($posts);
    }
}