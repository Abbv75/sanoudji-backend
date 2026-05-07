<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleSeeder::class);
    }

    /** @test */
    public function it_should_list_all_categories()
    {
        \App\Models\Category::factory(5)->create();

        $response = $this->getJson('/api/categories');

        $response->assertStatus(200)
            ->assertJsonStructure(['success', 'data', 'message'])
            ->assertJsonCount(5, 'data');
    }

    /** @test */
    public function it_should_show_a_single_category_with_books()
    {
        $category = \App\Models\Category::factory()->create();
        $author = \App\Models\Author::factory()->create();
        \App\Models\Book::factory(3)->create(['id_author' => $author->id])->each(function ($book) use ($category) {
            $book->categories()->attach($category->id);
        });

        $response = $this->getJson('/api/categories/' . $category->id);

        $response->assertStatus(200)
            ->assertJsonPath('data.id', $category->id)
            ->assertJsonCount(3, 'data.books');
    }

    /** @test */
    public function it_should_create_a_category_when_authenticated()
    {
        $user = \App\Models\User::factory()->create(['id_role' => 'R01']); // Admin
        
        $response = $this->actingAs($user, 'sanctum')->postJson('/api/categories', [
            'name' => 'Nouvelle Catégorie',
            'description' => 'Description de test'
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('data.name', 'Nouvelle Catégorie');
        
        $this->assertDatabaseHas('categories', ['name' => 'Nouvelle Catégorie']);
    }

    /** @test */
    public function it_should_fail_to_create_category_with_duplicate_name()
    {
        \App\Models\Category::factory()->create(['name' => 'Fiction']);
        $user = \App\Models\User::factory()->create(['id_role' => 'R01']);

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/categories', [
            'name' => 'Fiction'
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name'])
            ->assertJsonFragment(['name' => ['Ce nom de catégorie existe déjà.']]);
    }

    /** @test */
    public function it_should_update_a_category()
    {
        $category = \App\Models\Category::factory()->create(['name' => 'Ancien Nom']);
        $user = \App\Models\User::factory()->create(['id_role' => 'R01']);

        $response = $this->actingAs($user, 'sanctum')->putJson('/api/categories/' . $category->id, [
            'name' => 'Nouveau Nom'
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('data.name', 'Nouveau Nom');
    }

    /** @test */
    public function it_should_delete_a_category()
    {
        $category = \App\Models\Category::factory()->create();
        $user = \App\Models\User::factory()->create(['id_role' => 'R01']);

        $response = $this->actingAs($user, 'sanctum')->deleteJson('/api/categories/' . $category->id);

        $response->assertStatus(200);
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    /** @test */
    public function it_should_deny_access_to_unauthenticated_user_for_protected_routes()
    {
        $response = $this->postJson('/api/categories', ['name' => 'Interdit']);
        $response->assertStatus(401);
    }
}
