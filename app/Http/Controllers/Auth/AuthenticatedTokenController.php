<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\UserResource;
use App\Http\Responses\ApiSuccessResponse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class AuthenticatedTokenController extends Controller
{
    /**
     * @throws ValidationException
     */
    public function store(LoginRequest $request): ApiSuccessResponse
    {
        $request->authenticate();

        /** @var User $user */
        $user = $request->user();

        $token = $user->createToken('auth_token', ['*'], now()->addDay())->plainTextToken;

        return new ApiSuccessResponse(
            data: [
                'user' => new UserResource($user),
                'token' => $token,
            ],
            message: 'User successfully logged in'
        );
    }

    public function destroy(Request $request): Response
    {
        $request->user()->currentAccessToken()->delete();

        return response()->noContent();
    }
}
