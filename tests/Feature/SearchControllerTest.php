<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\SearchKeyword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_search_page_returns_successful_response(): void
    {
        $response = $this->get('/search');
        $response->assertStatus(200);
        $response->assertViewIs('search_results');
    }

    public function test_search_without_query_returns_empty_results(): void
    {
        $response = $this->get('/search');
        
        $response->assertStatus(200);
        $response->assertViewHas('results');
        $this->assertCount(0, $response['results']);
        $this->assertEquals('', $response['query']);
    }

    public function test_search_with_query_returns_matching_posts(): void
    {
        Post::factory(3)->create(['title' => 'Laravel Tutorial']);
        Post::factory(2)->create(['title' => 'PHP Guide']);

        $response = $this->get('/search?query=Laravel');

        $response->assertStatus(200);
        $response->assertViewHas('results');
        $this->assertCount(3, $response['results']);
        $response->assertSee('Laravel');
    }

    public function test_search_finds_posts_by_body(): void
    {
        Post::create([
            'title' => 'Test Post',
            'body' => 'This post contains elasticsearch content'
        ]);

        $response = $this->get('/search?query=elasticsearch');
        
        $this->assertCount(1, $response['results']);
        $this->assertEquals('Test Post', $response['results'][0]->title);
    }

    public function test_search_is_case_insensitive(): void
    {
        Post::create(['title' => 'Laravel Tutorial', 'body' => 'Content']);

        $response1 = $this->get('/search?query=laravel');
        $response2 = $this->get('/search?query=LARAVEL');
        $response3 = $this->get('/search?query=LaRaVeL');

        $this->assertCount(1, $response1['results']);
        $this->assertCount(1, $response2['results']);
        $this->assertCount(1, $response3['results']);
    }

    public function test_search_logs_keywords(): void
    {
        $this->assertDatabaseCount('search_keywords', 0);

        $this->get('/search?query=Laravel');
        $this->get('/search?query=Laravel');
        $this->get('/search?query=PHP');

        $this->assertDatabaseCount('search_keywords', 2);
        $this->assertDatabaseHas('search_keywords', [
            'keyword' => 'laravel',
            'count' => 2
        ]);
        $this->assertDatabaseHas('search_keywords', [
            'keyword' => 'php',
            'count' => 1
        ]);
    }

    public function test_search_does_not_log_short_queries(): void
    {
        $this->get('/search?query=a');
        $this->assertDatabaseCount('search_keywords', 0);
    }

    public function test_search_results_are_paginated(): void
    {
        Post::factory(15)->create(['title' => 'Laravel Post']);

        $response = $this->get('/search?query=Laravel');

        $this->assertCount(5, $response['results']);
        $response->assertViewHas('results');
    }

    public function test_search_trending_keywords_returned(): void
    {
        SearchKeyword::create(['keyword' => 'laravel', 'count' => 50]);
        SearchKeyword::create(['keyword' => 'php', 'count' => 30]);
        SearchKeyword::create(['keyword' => 'database', 'count' => 20]);

        $response = $this->get('/search');

        $response->assertViewHas('trending');
        $trending = $response['trending'];
        $this->assertCount(3, $trending);
        $this->assertEquals('laravel', $trending[0]->keyword);
        $this->assertEquals(50, $trending[0]->count);
    }

    public function test_search_shows_top_10_trending(): void
    {
        for ($i = 1; $i <= 15; $i++) {
            SearchKeyword::create(['keyword' => "keyword{$i}", 'count' => $i]);
        }

        $response = $this->get('/search');
        $this->assertCount(10, $response['trending']);
    }
}
