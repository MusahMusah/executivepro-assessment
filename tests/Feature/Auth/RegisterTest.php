<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Event;

describe('RegisterTest', function () {
    it('registers a user successfully and returns token and user resource', function () {
        Event::fake();

        $payload = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'secret1234',
            'password_confirmation' => 'secret1234',
        ];

        $response = $this->postJson(route('register'), $payload);

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'user' => [
                        'id', 'name', 'email',
                    ],
                    'token'
                ],
                'message'
            ])
            ->assertJson([
                'message' => 'User successfully registered'
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com'
        ]);

        Event::assertDispatched(Registered::class);
    });

    it('fails to register with existing email', function () {
        User::factory()->create([
            'email' => 'john@example.com',
        ]);

        $payload = [
            'name' => 'Another User',
            'email' => 'john@example.com',
            'password' => 'secret1234',
            'password_confirmation' => 'secret1234',
        ];

        $response = $this->postJson(route('register'), $payload);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['email'])
            ->assertJsonFragment([
                'email' => ['Email already exists, Please login with the email instead']
            ]);
    });

    it('fails validation when required fields are missing or invalid', function () {
        $response = $this->postJson(route('register'));

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['name', 'email', 'password']);
    });
});
