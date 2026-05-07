<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Register a new user.
     */
    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'firstName' => $request->firstName,
            'lastName' => $request->lastName,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'profilePhotoUrl' => $request->profilePhotoUrl,
            'id_role' => 'R02', // Client par défaut
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->success([
            'token' => $token,
            'user' => $user->load('role')
        ], 'Inscription réussie', 201);
    }

    /**
     * Authenticate user and return token.
     */
    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return $this->error('Identifiants invalides.', null, 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->success([
            'token' => $token,
            'user' => $user->load('role')
        ], 'Connexion réussie');
    }

    /**
     * Get the authenticated user.
     */
    public function me(Request $request)
    {
        return $this->success($request->user()->load('role'));
    }

    /**
     * Log out the user (revoke tokens).
     */
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return $this->success(null, 'Déconnexion réussie');
    }
}
