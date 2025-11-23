<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\AuthService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\RegisterRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthServiceTest extends TestCase
{
    use RefreshDatabase;

    protected AuthService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new AuthService();
    }

    public function test_it_can_register_user_without_image()
    {
        $request = new RegisterRequest([
            'first_name' => 'John',
            'last_name'  => 'Doe',
            'email'      => 'john@example.com',
            'password'   => 'secret123',
            'gender'     => 1,
        ]);

        $user = $this->service->register($request);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('John', $user->first_name);
        $this->assertEquals('Doe', $user->last_name);
        $this->assertNull($user->image);
        $this->assertTrue(Hash::check('secret123', $user->password));

        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com',
        ]);
    }

    public function test_it_can_register_user_with_image()
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->create('avatar.jpg', 200, 'image/jpeg');

        $request = RegisterRequest::create('/api/register', 'POST', [
            'first_name' => 'Jane',
            'last_name'  => 'Smith',
            'email'      => 'jane@example.com',
            'password'   => 'secret123',
            'gender'     => 2,
        ], [], [
            'image' => $file
        ]);

        $user = $this->service->register($request);

        $this->assertInstanceOf(User::class, $user);

        //Check file stored
        Storage::disk('public')->assertExists($user->image);

        //check daatabase
        $this->assertDatabaseHas('users', [
            'email' => 'jane@example.com'
        ]);
    }
}
