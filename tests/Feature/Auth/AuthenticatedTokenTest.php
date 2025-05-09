<?php

use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

describe('AuthenticatedToken', function () {
    beforeEach(function () {
        RateLimiter::clear((new LoginRequest())->throttleKey()); // Ensure no throttling from previous tests
    });

    it('logs in user with valid credentials and returns token and user data', function () {
        User::factory()->create([
            'email' => 'jane@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson(route('login'), [
            'email' => 'jane@example.com',
            'password' => 'password123',
        ]);

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'user' => ['id', 'name', 'email'],
                    'token',
                ],
                'message'
            ])
            ->assertJson([
                'message' => 'User successfully logged in',
            ]);
    });

    it('fails login with invalid credentials', function () {
        User::factory()->create([
            'email' => 'jane@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson(route('login'), [
            'email' => 'jane@example.com',
            'password' => 'wrong-password',
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors('email');
    });

    it('throttles login after too many attempts', function () {
        $email = 'throttle@example.com';

        // Simulate 5 failed attempts
        for ($i = 0; $i < 5; $i++) {
            $this->postJson(route('login'), [
                'email' => $email,
                'password' => 'wrong-password',
            ]);
        }

        $response = $this->postJson(route('login'), [
            'email' => $email,
            'password' => 'wrong-password',
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors('email')
            ->assertJsonFragment([
                'email' => [trans('auth.throttle', [
                    'seconds' => RateLimiter::availableIn(Str::lower($email).'|127.0.0.1'),
                    'minutes' => ceil(RateLimiter::availableIn(Str::lower($email).'|127.0.0.1') / 60),
                ])],
            ]);
    });

    it('logs out and deletes current access token', function () {
        $user = User::factory()->create();

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->post(route('logout'), [], [
            'Authorization' => 'Bearer ' . $token,
        ]);

        $response->assertNoContent();

        expect($user->tokens()->count())->toBe(0);
    });
});
