<?php

use App\Models\Product;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

beforeEach(function () {
    $this->user = User::factory()->create();
    Sanctum::actingAs($this->user);
});

it('retrieves user wishlists', function () {
    $product = Product::factory()->create();
    $this->user->wishlists()->create(['product_id' => $product->id]);

    $response = $this->getJson(route('wishlists.index'));

    $response->assertOk()
        ->assertJsonStructure([
            'data' => [
                '*' => ['id', 'created_at', 'updated_at', 'product'],
            ],
        ]);
});
