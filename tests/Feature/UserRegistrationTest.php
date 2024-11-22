<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_successfully()
    {
        Event::fake();  // Simula eventos para testar o disparo do evento Registered

        $data = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123'
        ];

        $response = $this->postJson('/api/register', $data);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'access_token',
                    'token_type',
                    'user' => [
                        'id',
                        'name',
                        'email',
                    ],
                ],
            ]);

        $this->assertDatabaseHas('users', [
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $user = User::where('email', 'test@example.com')->first();
        $this->assertTrue(Hash::check('password123', $user->password));

        Event::assertDispatched(Registered::class, function ($event) use ($user) {
            return $event->user->is($user);
        });
    }

    public function test_user_registration_fails_without_name()
    {
        $data = [
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123'
        ];

        $response = $this->postJson('/api/register', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('name');
    }

    public function test_user_registration_fails_without_email()
    {
        $data = [
            'name' => 'Test User',
            'password' => 'password123',
            'password_confirmation' => 'password123'
        ];

        $response = $this->postJson('/api/register', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('email');
    }

    public function test_user_registration_fails_with_existing_email()
    {
        $existingUser = User::factory()->create([
            'email' => 'test@example.com'
        ]);

        $data = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123'
        ];

        $response = $this->postJson('/api/register', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('email');
    }

    public function test_user_registration_fails_with_short_password()
    {
        $data = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'short',
            'password_confirmation' => 'short'
        ];

        $response = $this->postJson('/api/register', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('password');
    }
}
