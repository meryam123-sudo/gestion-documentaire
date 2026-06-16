<?php

namespace App\Http\Controllers;

use App\Models\CategorieDocument;
use Illuminate\Http\Request;

class CategorieDocumentController extends Controller
{
    // Liste catégories
    public function index()
    {
        return response()->json(
            CategorieDocument::all()
        );
    }

    // Ajouter catégorie
    public function store(Request $request)
    {
        $request->validate([
            'nomCategorie' => 'required|string|max:255|unique:categorie_documents,nomCategorie',
            'description' => 'nullable|string',
        ]);

        $categorie = CategorieDocument::create([
            'nomCategorie' => $request->nomCategorie,
            'description' => $request->description,
        ]);

        return response()->json([
            'message' => 'Catégorie ajoutée avec succès',
            'categorie' => $categorie
        ], 201);
    }

    // Afficher catégorie
    public function show($id)
    {
        $categorie = CategorieDocument::find($id);

        if (!$categorie) {
            return response()->json([
                'message' => 'Catégorie introuvable'
            ], 404);
        }

        return response()->json($categorie);
    }

    // Modifier catégorie
    public function update(Request $request, $id)
    {
        $categorie = CategorieDocument::find($id);

        if (!$categorie) {
            return response()->json([
                'message' => 'Catégorie introuvable'
            ], 404);
        }

        $request->validate([
            'nomCategorie' => 'required|string|max:255|unique:categorie_documents,nomCategorie,' . $id . ',idCategorie',
            'description' => 'nullable|string',
        ]);

        $categorie->update([
            'nomCategorie' => $request->nomCategorie,
            'description' => $request->description,
        ]);

        return response()->json([
            'message' => 'Catégorie modifiée avec succès',
            'categorie' => $categorie
        ]);
    }

    // Supprimer catégorie
    public function destroy($id)
    {
        $categorie = CategorieDocument::find($id);

        if (!$categorie) {
            return response()->json([
                'message' => 'Catégorie introuvable'
            ], 404);
        }

        $categorie->delete();

        return response()->json([
            'message' => 'Catégorie supprimée avec succès'
        ]);
    }
}