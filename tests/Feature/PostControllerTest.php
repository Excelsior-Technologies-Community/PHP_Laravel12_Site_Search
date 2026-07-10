<?php

namespace Tests\Feature;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_show_post_returns_successful_response(): void
    {
        $post = Post::create([
            'title' => 'Test Post',
            'body' => 'This is a test post body'
        ]);

        $response = $this->get("/posts/{$post->id}");

        $response->assertStatus(200);
        $response->assertViewIs('posts.show');
    }

    public function test_show_post_returns_post_data(): void
    {
        $post = Post::create([
            'title' => 'Laravel Testing',
            'body' => 'Learn how to test Laravel applications'
        ]);

        $response = $this->get("/posts/{$post->id}");

        $response->assertViewHas('post', $post);
        $response->assertViewHas('query', '');
        $response->assertSee('Laravel Testing');
        $response->assertSee('Learn how to test Laravel applications');
    }

    public function test_show_nonexistent_post_returns_404(): void
    {
        $response = $this->get('/posts/999');
        $response->assertStatus(404);
    }

    public function test_show_post_highlights_query_in_title(): void
    {
        $post = Post::create([
            'title' => 'Laravel Best Practices',
            'body' => 'Content here'
        ]);

        $response = $this->get("/posts/{$post->id}?query=Laravel");

        $highlightedTitle = $response['highlightedTitle'];
        $this->assertStringContainsString('<span class="highlight">Laravel</span>', $highlightedTitle);
    }

    public function test_show_post_highlights_query_in_body(): void
    {
        $post = Post::create([
            'title' => 'Test',
            'body' => 'This post is about Laravel testing'
        ]);

        $response = $this->get("/posts/{$post->id}?query=testing");

        $highlightedBody = $response['highlightedBody'];
        $this->assertStringContainsString('<span class="highlight">testing</span>', $highlightedBody);
    }

    public function test_show_post_highlights_case_insensitive(): void
    {
        $post = Post::create([
            'title' => 'Laravel Guide',
            'body' => 'Content'
        ]);

        $response = $this->get("/posts/{$post->id}?query=LARAVEL");

        $highlightedTitle = $response['highlightedTitle'];
        $this->assertStringContainsString('<span class="highlight">LARAVEL</span>', $highlightedTitle);
    }

    public function test_show_post_without_query_no_highlighting(): void
    {
        $post = Post::create([
            'title' => 'Laravel Guide',
            'body' => 'Content'
        ]);

        $response = $this->get("/posts/{$post->id}");

        $this->assertEquals('Laravel Guide', $response['highlightedTitle']);
        $this->assertNotEmpty($response['highlightedBody']);
        $this->assertStringNotContainsString('<span class="highlight">', $response['highlightedTitle']);
    }

    public function test_show_post_returns_query_parameter(): void
    {
        $post = Post::create([
            'title' => 'Test',
            'body' => 'Content'
        ]);

        $response = $this->get("/posts/{$post->id}?query=test");

        $response->assertViewHas('query', 'test');
    }
}
