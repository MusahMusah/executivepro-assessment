<?php

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "pest()" function to bind a different classes or traits.
|
*/

use App\Models\User;
use Laravel\Sanctum\Sanctum;

pest()->extend(Tests\TestCase::class)
  ->use(Illuminate\Foundation\Testing\RefreshDatabase::class)
    ->in('Feature');

function actingAsUser(): User
{
    $user = User::factory()->create();

    Sanctum::actingAs($user);

    return $user;
}
