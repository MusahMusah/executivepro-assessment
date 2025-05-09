<?php

use Illuminate\Testing\Fluent\AssertableJson;

describe('Me Endpoint', function () {
    it('can get the authenticated user', function () {
        $user = \App\Models\User::factory()->create();

        $this
            ->actingAs($user, 'sanctum')
            ->getJson(route('user.me'))
            ->assertOk()
            ->assertJson(fn(AssertableJson $json) => $json->hasAll(['data'])
                ->where('data.id', $user->id)
                ->where('data.email', $user->email)
            )
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'email',
                    'created_at',
                ],
            ]);
    });

    it('returns 401 when not authenticated', function () {
        $this
            ->getJson(route('user.me'))
            ->assertUnauthorized();
    });
});
