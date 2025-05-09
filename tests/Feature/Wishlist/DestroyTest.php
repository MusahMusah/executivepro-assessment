<?php

use App\Models\Product;
use App\Models\Wishlist;

it('deletes a wishlist item', function () {
    $user = actingAsUser();

    $product = Product::factory()->create();
    $wishlist = $user->wishlists()->create(['product_id' => $product->id]);

    $response = $this->deleteJson(route('wishlists.destroy', $wishlist));

    $response->assertNoContent();
    expect(Wishlist::find($wishlist->id))->toBeNull();
});