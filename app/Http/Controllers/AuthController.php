<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
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
     * Log out the user (revoke tokens).
     */
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return $this->success(null, 'Déconnexion réussie');
    }
}
