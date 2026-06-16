<?php

namespace App\Http\Controllers;

use App\Models\Dossier;
use Illuminate\Http\Request;

class DossierController extends Controller
{
    public function index()
    {
        return response()->json(Dossier::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'nomDossier' => 'required|string|max:255|unique:dossiers,nomDossier',
            'description' => 'nullable|string',
        ]);

        $dossier = Dossier::create([
            'nomDossier' => $request->nomDossier,
            'description' => $request->description,
        ]);

        return response()->json([
            'message' => 'Dossier ajouté avec succès',
            'dossier' => $dossier
        ], 201);
    }

    public function show($id)
    {
        $dossier = Dossier::find($id);

        if (!$dossier) {
            return response()->json([
                'message' => 'Dossier introuvable'
            ], 404);
        }

        return response()->json($dossier);
    }

    public function update(Request $request, $id)
    {
        $dossier = Dossier::find($id);

        if (!$dossier) {
            return response()->json([
                'message' => 'Dossier introuvable'
            ], 404);
        }

        $request->validate([
            'nomDossier' => 'required|string|max:255|unique:dossiers,nomDossier,' . $id . ',idDossier',
            'description' => 'nullable|string',
        ]);

        $dossier->update([
            'nomDossier' => $request->nomDossier,
            'description' => $request->description,
        ]);

        return response()->json([
            'message' => 'Dossier modifié avec succès',
            'dossier' => $dossier
        ]);
    }

    public function destroy($id)
    {
        $dossier = Dossier::find($id);

        if (!$dossier) {
            return response()->json([
                'message' => 'Dossier introuvable'
            ], 404);
        }

        $dossier->delete();

        return response()->json([
            'message' => 'Dossier supprimé avec succès'
        ]);
    }
}