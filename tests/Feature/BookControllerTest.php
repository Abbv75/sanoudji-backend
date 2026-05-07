<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BookControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleSeeder::class);
        \Illuminate\Support\Facades\Storage::fake('public');
    }

    /** @test */
    public function it_should_list_all_books()
    {
        $author = \App\Models\Author::factory()->create();
        \App\Models\Book::factory(15)->create(['id_author' => $author->id]);

        $response = $this->getJson('/api/books');

        $response->assertStatus(200)
            ->assertJsonStructure(['success', 'data', 'message'])
            ->assertJsonCount(15, 'data');
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
            ->assertJsonPath('data.author.id', $author->id);
    }

    /** @test */
    public function it_should_create_a_book_with_image_and_metadata()
    {
        $user = \App\Models\User::factory()->create(['id_role' => 'R01']); // Admin
        $author = \App\Models\Author::factory()->create();
        $category = \App\Models\Category::factory()->create();
        $attribute = \App\Models\MetadataAttribute::factory()->create(['dataType' => 'number']);
        
        $image = \Illuminate\Http\UploadedFile::fake()->image('cover.jpg');

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/books', [
            'title' => 'Nouveau Livre',
            'price' => 29.99,
            'stock' => 10,
            'id_author' => $author->id,
            'coverUrl' => $image,
            'categories' => [$category->id],
            'metadata' => [
                ['id_metadata_attribute' => $attribute->id, 'value' => '500']
            ]
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('data.title', 'Nouveau Livre');
        
        $this->assertDatabaseHas('books', ['title' => 'Nouveau Livre']);
        $this->assertDatabaseHas('book_metadata', ['value' => '500']);
        
        \Illuminate\Support\Facades\Storage::disk('public')->assertExists('images/' . $image->hashName());
    }

    /** @test */
    public function it_should_update_a_book_and_replace_image()
    {
        $user = \App\Models\User::factory()->create(['id_role' => 'R01']);
        $author = \App\Models\Author::factory()->create();
        $book = \App\Models\Book::factory()->create(['id_author' => $author->id]);
        
        $oldImage = \Illuminate\Http\UploadedFile::fake()->image('old.jpg');
        $book->update(['coverUrl' => \Illuminate\Support\Facades\Storage::url($oldImage->store('images', 'public'))]);

        $newImage = \Illuminate\Http\UploadedFile::fake()->image('new.jpg');

        $response = $this->actingAs($user, 'sanctum')->putJson('/api/books/' . $book->id, [
            'title' => 'Titre Modifié',
            'coverUrl' => $newImage
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('books', ['title' => 'Titre Modifié']);
        
        Storage::disk('public')->assertExists('images/' . $newImage->hashName());
        Storage::disk('public')->assertMissing('images/' . $oldImage->hashName());
    }

    /** @test */
    public function it_should_delete_a_book_and_its_image()
    {
        $user = \App\Models\User::factory()->create(['id_role' => 'R01']);
        $author = \App\Models\Author::factory()->create();
        $image = \Illuminate\Http\UploadedFile::fake()->image('to_delete.jpg');
        $book = \App\Models\Book::factory()->create([
            'id_author' => $author->id,
            'coverUrl' => \Illuminate\Support\Facades\Storage::url($image->store('images', 'public'))
        ]);

        $response = $this->actingAs($user, 'sanctum')->deleteJson('/api/books/' . $book->id);

        $response->assertStatus(200);
        $this->assertDatabaseMissing('books', ['id' => $book->id]);
        \Illuminate\Support\Facades\Storage::disk('public')->assertMissing('images/' . $image->hashName());
    }

    /** @test */
    public function it_should_return_404_for_non_existent_book()
    {
        $response = $this->getJson('/api/books/' . \Illuminate\Support\Str::uuid());

        $response->assertStatus(404);
    }
}
