<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterUserRequest;
use App\Http\Resources\UserRegisterResource;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{

    public function store(RegisterUserRequest $request): \Illuminate\Http\JsonResponse
    {
        $request->validateResolved();

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->string('password')),
        ]);

        event(new Registered($user));

        $token =  $user->createToken('auth_token')->plainTextToken;

        return (new UserRegisterResource((object)[
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ]))->response()->setStatusCode(Response::HTTP_CREATED);
    }
}
