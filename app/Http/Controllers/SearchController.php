<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\SearchKeyword;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        // Get search query
        $query = $request->input('query', '');

        /*
        |--------------------------------------------------------------------------
        | Save Search Keyword Count
        |--------------------------------------------------------------------------
        */
        if (!empty($query)) {

            $keyword = SearchKeyword::firstOrCreate([
                'keyword' => $query
            ]);

            $keyword->increment('count');
        }


        /*
        |--------------------------------------------------------------------------
        | Search Posts
        |--------------------------------------------------------------------------
        */
        $results = collect();

        if (!empty($query)) {

            $results = Post::where('title', 'like', "%{$query}%")
                            ->orWhere('body', 'like', "%{$query}%")
                            ->get();
        }


        /*
        |--------------------------------------------------------------------------
        | Get Top Trending Searches
        |--------------------------------------------------------------------------
        */
        $trending = SearchKeyword::orderBy('count', 'DESC')
                        ->take(5)
                        ->get();


        /*
        |--------------------------------------------------------------------------
        | Return Search Result View
        |--------------------------------------------------------------------------
        */
        return view(
            'search_results',
            compact(
                'results',
                'query',
                'trending'
            )
        );
    }
}