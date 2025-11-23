<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_login_successfully()
    {
        // Prepare user
        $user = User::factory()->create([
            'email' => 'john@example.com',
            'password' => Hash::make('secret123'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'john@example.com',
            'password' => 'secret123'
        ]);

        $response->assertStatus(201)->assertJsonStructure([
            'message',
            'results' => [
                'user',
                'token'
            ]
        ]);

        $this->assertNotNull($response->json('results.token'));
    }

    public function test_login_fails_with_invalid_password()
    {
        User::factory([
            'email' => 'john@example.com',
            'password' => 'password123'
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'john@example.com',
            'password' => 'password122'
        ]);

        $response->assertStatus(401)->assertJson([
            'message' => 'These credentials do not match our records.'
        ]);
    }

    public function test_login_fails_with_email_not_registered()
    {
        $response = $this->postJson('/api/login', [
            'email' => 'johndoe@gmail.com',
            'password' => 'password'
        ]);

        $response->assertStatus(401)->assertJson([
            'message' => 'These credentials do not match our records.'
        ]);
    }
}
