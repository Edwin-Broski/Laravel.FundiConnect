<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Provider;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'phone'    => 'nullable|string|unique:users',
            'email'    => 'nullable|email|unique:users',
            'password' => 'required|string|min:6',
            'role'     => 'required|in:customer,provider',
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'phone'    => $data['phone'] ?? null,
            'email'    => $data['email'] ?? null,
            'password' => $data['password'],
            'role'     => $data['role'],
        ]);

        // if registering as provider, create empty provider profile
        if ($user->role === 'provider') {
            Provider::create(['user_id' => $user->id]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user'  => $user,
            'token' => $token,
        ], 201);
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'login'    => 'required|string', // phone or email
            'password' => 'required|string',
        ]);

        $user = User::where('email', $data['login'])
                    ->orWhere('phone', $data['login'])
                    ->first();

        if (! $user || ! Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'login' => ['Incorrect credentials.'],
            ]);
        }

        if (! $user->is_active) {
            return response()->json(['message' => 'Account suspended.'], 403);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user'  => $user,
            'token' => $token,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully.']);
    }

    public function me(Request $request)
    {
        return response()->json($request->user()->load('provider.trades'));
    }
}