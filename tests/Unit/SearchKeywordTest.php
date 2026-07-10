<?php

namespace Tests\Unit;

use App\Models\SearchKeyword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchKeywordTest extends TestCase
{
    use RefreshDatabase;

    public function test_search_keyword_can_be_created(): void
    {
        $keyword = SearchKeyword::create([
            'keyword' => 'laravel',
            'count' => 1
        ]);

        $this->assertNotNull($keyword->id);
        $this->assertEquals('laravel', $keyword->keyword);
        $this->assertEquals(1, $keyword->count);
    }

    public function test_search_keyword_mass_assignment(): void
    {
        SearchKeyword::create([
            'keyword' => 'testing',
            'count' => 5
        ]);

        $this->assertDatabaseHas('search_keywords', [
            'keyword' => 'testing',
            'count' => 5
        ]);
    }

    public function test_search_keyword_fillable_attributes(): void
    {
        $fillable = (new SearchKeyword())->getFillable();
        $this->assertContains('keyword', $fillable);
        $this->assertContains('count', $fillable);
    }

    public function test_search_keyword_count_can_be_updated(): void
    {
        $keyword = SearchKeyword::create([
            'keyword' => 'php',
            'count' => 1
        ]);

        $keyword->count += 1;
        $keyword->save();

        $this->assertEquals(2, $keyword->count);
        $this->assertDatabaseHas('search_keywords', [
            'keyword' => 'php',
            'count' => 2
        ]);
    }

    public function test_multiple_search_keywords_can_be_created(): void
    {
        SearchKeyword::create(['keyword' => 'laravel', 'count' => 10]);
        SearchKeyword::create(['keyword' => 'php', 'count' => 8]);
        SearchKeyword::create(['keyword' => 'database', 'count' => 5]);

        $this->assertDatabaseCount('search_keywords', 3);
    }
}
