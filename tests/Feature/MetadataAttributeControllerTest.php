<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MetadataAttributeControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleSeeder::class);
    }

    /** @test */
    public function it_should_list_all_attributes()
    {
        \App\Models\MetadataAttribute::factory(3)->create();

        $response = $this->getJson('/api/attributes');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    /** @test */
    public function it_should_create_an_attribute()
    {
        $user = \App\Models\User::factory()->create(['id_role' => 'R01']);

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/attributes', [
            'name' => 'Poids',
            'dataType' => 'number'
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('data.name', 'Poids');
        
        $this->assertDatabaseHas('metadata_attributes', ['name' => 'Poids']);
    }

    /** @test */
    public function it_should_fail_creation_with_invalid_type()
    {
        $user = \App\Models\User::factory()->create(['id_role' => 'R01']);

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/attributes', [
            'name' => 'Invalide',
            'dataType' => 'unknown'
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['dataType'])
            ->assertJsonFragment(['dataType' => ['Le type doit être : text, number, date ou boolean.']]);
    }

    /** @test */
    public function it_should_update_an_attribute()
    {
        $attribute = \App\Models\MetadataAttribute::factory()->create(['name' => 'Vieux Nom']);
        $user = \App\Models\User::factory()->create(['id_role' => 'R01']);

        $response = $this->actingAs($user, 'sanctum')->putJson('/api/attributes/' . $attribute->id, [
            'name' => 'Nouveau Nom'
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('data.name', 'Nouveau Nom');
    }

    /** @test */
    public function it_should_delete_an_attribute()
    {
        $attribute = \App\Models\MetadataAttribute::factory()->create();
        $user = \App\Models\User::factory()->create(['id_role' => 'R01']);

        $response = $this->actingAs($user, 'sanctum')->deleteJson('/api/attributes/' . $attribute->id);

        $response->assertStatus(200);
        $this->assertDatabaseMissing('metadata_attributes', ['id' => $attribute->id]);
    }
}
