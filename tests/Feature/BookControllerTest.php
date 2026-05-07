<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BookControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_list_paginated_books()
    {
        $author = \App\Models\Author::factory()->create();
        \App\Models\Book::factory(20)->create(['id_author' => $author->id]);

        $response = $this->getJson('/api/books');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    '*' => ['id', 'title', 'author', 'categories']
                ],
                'message'
            ]);
        
        $this->assertCount(20, $response->json('data'));
    }

    /** @test */
    public function it_should_show_a_single_book_with_details()
    {
        $author = \App\Models\Author::factory()->create();
        $book = \App\Models\Book::factory()->create(['id_author' => $author->id]);
        $category = \App\Models\Category::factory()->create();
        $book->categories()->attach($category->id);

        $response = $this->getJson('/api/books/' . $book->id);

        $response->assertStatus(200)
            ->assertJsonPath('data.id', $book->id)
            ->assertJsonPath('data.author.id', $author->id)
            ->assertJsonCount(1, 'data.categories');
    }

    /** @test */
    public function it_should_return_404_for_non_existent_book()
    {
        $response = $this->getJson('/api/books/' . \Illuminate\Support\Str::uuid());

        $response->assertStatus(404)
            ->assertJson(['success' => false, 'message' => 'Livre non trouvé']);
    }
}
