<?php

declare(strict_types=1);

namespace App\Actions;

use App\Http\Resources\WishlistResource;
use Illuminate\Http\JsonResponse;
use Spatie\QueryBuilder\QueryBuilder;

class GetUserWishlistAction
{
    public function __invoke(): JsonResponse
    {
        $query = request()->user()->wishlists()->with(['product']);

        return WishlistResource::collection(
            QueryBuilder::for($query)
                ->allowedFilters([
                    'name',
                ])
                ->allowedSorts(['created_at'])
                ->paginate()
                ->appends(request()->query())
        )->toResponse(request());
    }
}