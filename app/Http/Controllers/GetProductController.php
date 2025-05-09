<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class GetProductController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        return ProductResource::collection(
            QueryBuilder::for(Product::class)
                ->allowedFilters([
                    'name',
                ])
                ->allowedSorts(['name'])
                ->paginate()
                ->appends(request()->query())
        )->toResponse(request());
    }
}
