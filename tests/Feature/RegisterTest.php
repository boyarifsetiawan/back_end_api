<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;

class RegisterTest extends TestCase
{
    use RefreshDatabase;


    public function test_user_can_register_successfully()
    {
        $payload = [
            'first_name' => 'John',
            'last_name'  => 'Doe',
            'gender'     => 1,
            'email'      => 'john@example.com',
            'password'   => 'password123',
        ];

        $repsonse = $this->postJson('/api/register', $payload);

        $repsonse->assertStatus(201)->assertJson([
            'message' => 'Registration Successful!',
            'results' => [
                'user' => [
                    'first_name' => 'John',
                    'last_name' => 'Doe',
                    'email' => 'john@example.com'
                ]
            ]
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com'
        ]);
    }

    public function test_registration_fails_if_email_already_taken()
    {
        User::factory()->create([
            'email' => 'john@example.com'
        ]);

        $payload = [
            'first_name' => 'John',
            'last_name'  => 'Doe',
            'gender'     => 1,
            'email'      => 'john@example.com',
            'password'   => 'password123',
        ];

        $response  =  $this->postJson('/api/register', $payload);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('email');
    }

    public function test_registration_fails_if_required_fields_missing()
    {
        $response = $this->postJson('/api/register', []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors([
            'first_name',
            'last_name',
            'gender',
            'email',
            'password'
        ]);
    }

    public function test_registration_with_image_upload()
    {
        Storage::fake('public');
        $payload = [
            'first_name' => 'John',
            'last_name'  => 'Doe',
            'gender'     => 1,
            'email'      => 'johnimg@example.com',
            'password'   => 'password123',
            'image'      => UploadedFile::fake()->image('avatar.jpg'),
        ];

        $response = $this->postJson('/api/register', $payload);
        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'results' => [
                    'user' => [
                        'id',
                        'first_name',
                        'last_name',
                        'email',
                        'image'
                    ]

                ]
            ]);

        $userImagePath = User::first()->image;
        Storage::disk('public')->assertExists($userImagePath);

        $this->assertDatabaseHas('users', [
            'email' => 'johnimg@example.com'
        ]);
    }

    public function test_registration_fails_if_upload_file_is_not_image()
    {
        Storage::disk('public');

        $payload = [
            'first_name' => 'John',
            'last_name'  => 'Doe',
            'gender'     => 1,
            'email'      => 'wrongfile@example.com',
            'password'   => 'password123',
            'image'      => UploadedFile::fake()->create('document.pdf', 100, 'application/pdf'),

        ];

        $response = $this->postJson('/api/register', $payload);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('image');
    }
}
