<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('query', '');

        if (empty($query)) {
            return view('search_results', [
                'results' => collect(),
                'query' => ''
            ]);
        }

        // Only fetch posts that contain the exact phrase in title OR body
        $results = Post::where('title', 'like', "%{$query}%")
            ->orWhere('body', 'like', "%{$query}%")
            ->get();

        return view('search_results', compact('results', 'query'));
    }
}