<?php

use App\Models\Product;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;

beforeEach(function () {
    $this->user = User::factory()->create();
    Sanctum::actingAs($this->user);
});

describe('Product Index', function () {
    it('can show the product index page', function () {
        Product::factory(10)->create();

        $this
            ->getJson(route('products.index'))
            ->assertOk()
            ->assertJsonCount(10, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'description',
                        'price',
                        'created_at',
                    ]
                ]
            ]);
    });

    it('returns a paginated list of products', function () {
        Product::factory()->count(15)->create();

        $this->getJson(route('products.index'))
            ->assertOk()->assertJson(fn(AssertableJson $json) => $json->hasAll(['data', 'links', 'meta']));
    });

    it('can filter products by name', function () {
        Product::factory()->create(['name' => 'MacBook']);
        Product::factory()->create(['name' => 'iPhone']);

        $response = $this->getJson('/api/products?filter[name]=MacBook');

        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(['name' => 'MacBook']);
    });

    it('can sort products by name ascending', function () {
        Product::factory()->create(['name' => 'Zebra']);
        Product::factory()->create(['name' => 'Apple']);

        $response = $this->getJson('/api/products?sort=name')->assertOk();

        expect($response->json('data.0.name'))->toBe('Apple');
    });

    it('can sort products by name descending', function () {
        Product::factory()->create(['name' => 'Zebra']);
        Product::factory()->create(['name' => 'Apple']);

        $response = $this->getJson('/api/products?sort=-name');

        $response->assertOk();
        expect($response->json('data.0.name'))->toBe('Zebra');
    });
});
