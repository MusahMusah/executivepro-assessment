<?php

use App\Http\Controllers\Auth\AuthenticatedTokenController;
use App\Http\Controllers\Auth\RegisterUserController;
use App\Http\Controllers\GetProductController;
use App\Http\Controllers\WishlistController;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Route;

Route::post('/register', RegisterUserController::class)->name('register');
Route::post('/login', [AuthenticatedTokenController::class, 'store'])->name('login');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthenticatedTokenController::class, 'destroy'])->name('logout');
    Route::get('/me', fn () => new UserResource(auth()->user()))->name('user.me');

    Route::prefix('products')
        ->as('products.')
        ->group(function () {
            Route::get('/', GetProductController::class)->name('index');
        });

    Route::prefix('wishlists')
        ->as('wishlists.')
        ->group(function () {
            Route::get('/', [WishlistController::class, 'index'])->name('index');
            Route::post('/', [WishlistController::class, 'store'])->name('store');
            Route::delete('/{wishlist}', [WishlistController::class, 'destroy'])->name('destroy');
        });
});
