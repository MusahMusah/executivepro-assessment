<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegistrationRequest;
use App\Http\Resources\UserResource;
use App\Http\Responses\ApiSuccessResponse;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;

class RegisterUserController extends Controller
{
    public function __invoke(RegistrationRequest $request): ApiSuccessResponse
    {
        $user = User::query()->create([
            ...$request->validated(),
            'password' => Hash::make($request->string('password'))
        ]);

        event(new Registered($user));

        $token = $user->createToken("register:user{$user->id}")->plainTextToken;

        return new ApiSuccessResponse(
            data: [
                'user' => new UserResource($user),
                'token' => $token,
            ],
            message: 'User successfully registered'
        );
    }
}
