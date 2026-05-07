<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleSeeder::class);
        Storage::fake('public');
    }

    /** @test */
    public function it_should_list_users()
    {
        $admin = User::factory()->create(['id_role' => 'R01']);
        User::factory(5)->create(['id_role' => 'R02']);

        $response = $this->actingAs($admin, 'sanctum')->getJson('/api/users');

        $response->assertStatus(200)
            ->assertJsonCount(6, 'data');
    }

    /** @test */
    public function it_should_create_a_user_with_photo()
    {
        $admin = User::factory()->create(['id_role' => 'R01']);
        $photo = UploadedFile::fake()->image('profile.jpg');

        $response = $this->actingAs($admin, 'sanctum')->postJson('/api/users', [
            'firstName' => 'John',
            'lastName' => 'Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'id_role' => 'R02',
            'profilePhotoUrl' => $photo
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('data.email', 'john@example.com');

        $this->assertDatabaseHas('users', ['email' => 'john@example.com']);
        Storage::disk('public')->assertExists('profiles/' . $photo->hashName());
    }

    /** @test */
    public function it_should_update_a_user()
    {
        $admin = User::factory()->create(['id_role' => 'R01']);
        $user = User::factory()->create(['id_role' => 'R02']);

        $response = $this->actingAs($admin, 'sanctum')->putJson('/api/users/' . $user->id, [
            'firstName' => 'UpdatedName'
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('users', ['id' => $user->id, 'firstName' => 'UpdatedName']);
    }

    /** @test */
    public function it_should_delete_a_user_and_photo()
    {
        $admin = User::factory()->create(['id_role' => 'R01']);
        $photo = UploadedFile::fake()->image('avatar.jpg');
        $user = User::factory()->create([
            'id_role' => 'R02',
            'profilePhotoUrl' => Storage::url($photo->store('profiles', 'public'))
        ]);

        $response = $this->actingAs($admin, 'sanctum')->deleteJson('/api/users/' . $user->id);

        $response->assertStatus(200);
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
        Storage::disk('public')->assertMissing('profiles/' . $photo->hashName());
    }

    /** @test */
    public function unauthorized_user_cannot_access_user_crud()
    {
        $response = $this->getJson('/api/users');
        $response->assertStatus(401);
    }
}
