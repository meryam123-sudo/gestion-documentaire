<?php

namespace App\Http\Controllers;

use App\Models\Utilisateur;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'mot_de_passe' => 'required',
        ]);

        $utilisateur = Utilisateur::with('role')
            ->where('email', $request->email)
            ->first();

        if (!$utilisateur) {
            return response()->json([
                'message' => 'Identifiant incorrect'
            ], 401);
        }

        if (!Hash::check($request->mot_de_passe, $utilisateur->mot_de_passe)) {
            return response()->json([
                'message' => 'Mot de passe incorrect'
            ], 401);
        }

        if (!$utilisateur->actif) {
            return response()->json([
                'message' => 'Compte désactivé'
            ], 403);
        }

        $token = $utilisateur->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Connexion réussie',
            'token' => $token,
            'utilisateur' => $utilisateur
        ]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:utilisateurs,email',
            'mot_de_passe' => 'required|string|min:6',
        ]);

        $role = Role::where(
            'nomRole',
            'Gestionnaire documentaire'
        )->first();

        if (!$role) {
            return response()->json([
                'message' => 'Rôle Gestionnaire documentaire introuvable'
            ], 404);
        }

        $utilisateur = Utilisateur::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'mot_de_passe' => Hash::make($request->mot_de_passe),
            'actif' => true,
            'idRole' => $role->idRole,
        ]);

        $utilisateur->load('role');

        $token = $utilisateur->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Inscription réussie',
            'token' => $token,
            'utilisateur' => $utilisateur
        ], 201);
    }

    public function me(Request $request)
    {
        return response()->json([
            'utilisateur' => $request->user()->load('role')
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Déconnexion réussie'
        ]);
    }
}