<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index()
    {
        $users = User::with('role')->latest()->get();
        return $this->success($users, 'Liste des utilisateurs récupérée avec succès');
    }

    /**
     * Store a newly created user.
     */
    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);

        if ($request->hasFile('profilePhotoUrl')) {
            $path = $request->file('profilePhotoUrl')->store('profiles', 'public');
            $data['profilePhotoUrl'] = Storage::url($path);
        }

        $user = User::create($data);

        return $this->success($user->load('role'), 'Utilisateur créé avec succès', 201);
    }

    /**
     * Display the specified user.
     */
    public function show(string $id)
    {
        $user = User::with(['role', 'orders', 'reviews'])->find($id);

        if (!$user) {
            return $this->notFound('Utilisateur non trouvé');
        }

        return $this->success($user, 'Détails de l\'utilisateur récupérés avec succès');
    }

    /**
     * Update the specified user.
     */
    public function update(UpdateUserRequest $request, string $id)
    {
        $user = User::find($id);

        if (!$user) {
            return $this->notFound('Utilisateur non trouvé');
        }

        $data = $request->validated();

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        if ($request->hasFile('profilePhotoUrl')) {
            if ($user->profilePhotoUrl) {
                $oldPath = str_replace('/storage/', '', $user->profilePhotoUrl);
                Storage::disk('public')->delete($oldPath);
            }

            $path = $request->file('profilePhotoUrl')->store('profiles', 'public');
            $data['profilePhotoUrl'] = Storage::url($path);
        }

        $user->update($data);

        return $this->success($user->load('role'), 'Utilisateur mis à jour avec succès');
    }

    /**
     * Remove the specified user.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);

        if (!$user) {
            return $this->notFound('Utilisateur non trouvé');
        }

        if ($user->profilePhotoUrl) {
            $oldPath = str_replace('/storage/', '', $user->profilePhotoUrl);
            Storage::disk('public')->delete($oldPath);
        }

        $user->delete();

        return $this->success(null, 'Utilisateur supprimé avec succès');
    }
}
