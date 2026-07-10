<?php

namespace Tests\Unit;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    public function test_post_can_be_created(): void
    {
        $post = Post::create([
            'title' => 'Test Post',
            'body' => 'This is test content'
        ]);

        $this->assertNotNull($post->id);
        $this->assertEquals('Test Post', $post->title);
        $this->assertEquals('This is test content', $post->body);
    }

    public function test_post_mass_assignment(): void
    {
        $post = Post::create([
            'title' => 'Laravel',
            'body' => 'PHP Framework'
        ]);

        $this->assertTrue($post->wasRecentlyCreated);
        $this->assertDatabaseHas('posts', [
            'title' => 'Laravel',
            'body' => 'PHP Framework'
        ]);
    }

    public function test_get_excerpt_default_length(): void
    {
        $post = Post::create([
            'title' => 'Test',
            'body' => str_repeat('a', 300)
        ]);

        $excerpt = $post->getExcerpt();
        $this->assertLessThanOrEqual(203, strlen($excerpt)); // 200 + "..."
    }

    public function test_get_excerpt_custom_length(): void
    {
        $post = Post::create([
            'title' => 'Test',
            'body' => 'Lorem ipsum dolor sit amet'
        ]);

        $excerpt = $post->getExcerpt(10);
        $this->assertLessThanOrEqual(13, strlen($excerpt)); // 10 + "..."
    }

    public function test_get_excerpt_removes_html_tags(): void
    {
        $post = Post::create([
            'title' => 'Test',
            'body' => '<h1>Title</h1><p>This is a paragraph</p>'
        ]);

        $excerpt = $post->getExcerpt(50);
        $this->assertStringNotContainsString('<h1>', $excerpt);
        $this->assertStringNotContainsString('</h1>', $excerpt);
        $this->assertStringNotContainsString('<p>', $excerpt);
    }

    public function test_get_excerpt_short_body_returns_full_text(): void
    {
        $post = Post::create([
            'title' => 'Test',
            'body' => 'Short text'
        ]);

        $excerpt = $post->getExcerpt(200);
        $this->assertEquals('Short text', $excerpt);
    }

    public function test_post_fillable_attributes(): void
    {
        $fillable = (new Post())->getFillable();
        $this->assertContains('title', $fillable);
        $this->assertContains('body', $fillable);
    }
}
