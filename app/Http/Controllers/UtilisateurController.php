<?php

namespace App\Http\Controllers;

use App\Models\Utilisateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UtilisateurController extends Controller
{
    // Liste utilisateurs
    public function index()
    {
        return response()->json(
            Utilisateur::with('role')->get()
        );
    }

    // Ajouter utilisateur
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:utilisateurs,email',
            'mot_de_passe' => 'required|min:6',
            'idRole' => 'required|exists:roles,idRole',
        ]);

        $utilisateur = Utilisateur::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'mot_de_passe' => Hash::make($request->mot_de_passe),
            'actif' => true,
            'idRole' => $request->idRole,
        ]);

        return response()->json([
            'message' => 'Utilisateur ajouté avec succès',
            'utilisateur' => $utilisateur
        ], 201);
    }

    // Afficher utilisateur
    public function show($id)
    {
        $utilisateur = Utilisateur::with('role')->find($id);

        if (!$utilisateur) {
            return response()->json([
                'message' => 'Utilisateur introuvable'
            ], 404);
        }

        return response()->json($utilisateur);
    }

    // Modifier utilisateur
    public function update(Request $request, $id)
    {
        $utilisateur = Utilisateur::find($id);

        if (!$utilisateur) {
            return response()->json([
                'message' => 'Utilisateur introuvable'
            ], 404);
        }

        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:utilisateurs,email,' . $id . ',idUtilisateur',
            'idRole' => 'required|exists:roles,idRole',
        ]);

        $utilisateur->update([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'idRole' => $request->idRole,
        ]);

        return response()->json([
            'message' => 'Utilisateur modifié avec succès',
            'utilisateur' => $utilisateur
        ]);
    }

    // Désactiver utilisateur
    public function destroy($id)
    {
        $utilisateur = Utilisateur::find($id);

        if (!$utilisateur) {
            return response()->json([
                'message' => 'Utilisateur introuvable'
            ], 404);
        }

        $utilisateur->update([
            'actif' => false
        ]);

        return response()->json([
            'message' => 'Utilisateur désactivé avec succès'
        ]);
    }
}