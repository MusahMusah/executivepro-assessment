<?php

use App\Models\Product;

it('adds product to wishlist', function () {
    $user = actingAsUser();

    $product = Product::factory()->create();

    $response = $this->postJson(route('wishlists.store'), [
        'product_id' => $product->id,
    ]);

    $response->assertOk()
        ->assertJsonPath('message', 'Product added to wishlist successfully.')
        ->assertJsonStructure(['data', 'message']);

    expect($user->wishlists()->where('product_id', $product->id)->exists())->toBeTrue();
});

it('fails to add wishlist with invalid product_id', function () {
    actingAsUser();
    
    $response = $this->postJson(route('wishlists.store'), [
        'product_id' => 9999,
    ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors('product_id');
});