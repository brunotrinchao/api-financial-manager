<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserRegisterResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')
                ->stateless()
                ->user();

            $user = User::firstOrCreate(
                ['email' => $googleUser->getEmail()],
                [
                    'name' => $googleUser->getName(),
                    'avatar' => $googleUser->getAvatar(),
                    'password' => Hash::make($googleUser->getEmail())
                ]
            );

            $token = $user->createToken('google_token')->plainTextToken;

            $ret = new UserRegisterResource((object)[
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => $user
            ]);

            return redirect(env('FRONTEND_URL') . '/auth/callback?ret=' . json_encode($ret));

        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao autenticar com o Google.', $e->getMessage()], 500);
        }
    }
}
