<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\SearchKeyword;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('query', '');

        if (!empty($query) && session('last_search') !== $query) {
            $keyword = SearchKeyword::firstOrCreate([
                'keyword' => $query
            ]);

            $keyword->increment('count');

            session(['last_search' => $query]);
        }

        $results = collect();

        if (!empty($query)) {
            $results = Post::where('title', 'like', "%{$query}%")
                ->orWhere('body', 'like', "%{$query}%")
                ->paginate(6)
                ->appends(['query' => $query]);
        }

        $trending = SearchKeyword::orderBy('count', 'DESC')
            ->take(5)
            ->get();

        return view(
            'search_results',
            compact('results', 'query', 'trending')
        );
    }
}