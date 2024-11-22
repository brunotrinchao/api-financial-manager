<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function show($id)
    {
        $user = User::findOrFail($id);
        return UserResource::make($user)->response();
    }

    public function update(UserUpdateRequest $request, $id)
    {
        $user = User::findOrFail($id);
        $user->fill($request->only(['name', 'email']));

        if ($request->hasFile('avatar')) {
            if ($user->profile_picture) {
                Storage::delete($user->profile_picture);
            }
            $user->profile_picture = $request->file('avatar')->store('avatar');
        }

        if ($request->filled('password')) {
            $user->password = Hash::make($request->input('password'));
        }

        $user->save();
        return UserResource::make($user)->response();
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(['message' => 'User deleted successfully']);
    }
}
