<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Les rôles sont nécessaires pour l'inscription/connexion
        $this->seed(\Database\Seeders\RoleSeeder::class);
    }

    /** @test */
    public function it_should_register_a_new_user()
    {
        $userData = [
            'firstName' => 'John',
            'lastName' => 'Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->postJson('/api/register', $userData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'token',
                    'user' => ['id', 'firstName', 'lastName', 'email', 'id_role']
                ],
                'message' => []
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com',
            'firstName' => 'John'
        ]);
    }

    /** @test */
    public function it_should_fail_registration_if_email_exists()
    {
        \App\Models\User::factory()->create(['email' => 'john@example.com', 'id_role' => 'R02']);

        $userData = [
            'firstName' => 'John',
            'lastName' => 'Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->postJson('/api/register', $userData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    /** @test */
    public function it_should_login_existing_user()
    {
        $user = \App\Models\User::factory()->create([
            'email' => 'jane@example.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password123'),
            'id_role' => 'R02'
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'jane@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => ['token', 'user'],
                'message'
            ]);
    }

    /** @test */
    public function it_should_fail_login_with_wrong_password()
    {
        \App\Models\User::factory()->create([
            'email' => 'jane@example.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password123'),
            'id_role' => 'R02'
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'jane@example.com',
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'success' => false,
                'message' => 'Identifiants invalides.'
            ]);
    }

    /** @test */
    public function it_should_return_user_profile_when_authenticated()
    {
        $user = \App\Models\User::factory()->create(['id_role' => 'R02']);
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/me');

        $response->assertStatus(200)
            ->assertJsonPath('data.email', $user->email);
    }

    /** @test */
    public function it_should_logout_user()
    {
        $user = \App\Models\User::factory()->create(['id_role' => 'R02']);
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/logout');

        $response->assertStatus(200)
            ->assertJson(['success' => true, 'message' => 'Déconnexion réussie']);

        $this->assertCount(0, $user->fresh()->tokens);
    }
}
