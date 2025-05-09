<?php

namespace App\Http\Controllers;

use App\Actions\GetUserWishlistAction;
use App\Http\Requests\WishlistRequest;
use App\Http\Responses\ApiSuccessResponse;
use App\Models\Wishlist;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class WishlistController extends Controller
{
    public function index(GetUserWishlistAction $action): JsonResponse
    {
        return $action();
    }

    public function store(WishlistRequest $request): ApiSuccessResponse
    {
        $request->user()->wishlists()->updateOrCreate($request->validated());

        return new ApiSuccessResponse(
            data: $request->user()->wishlists->fresh(),
            message: 'Product added to wishlist successfully.',
        );
    }

    public function destroy(Wishlist $wishlist): Response
    {
        $wishlist->delete();

        return response()->noContent();
    }
}
