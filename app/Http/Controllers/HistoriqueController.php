<?php

namespace App\Http\Controllers;

use App\Models\Document;

class HistoriqueController extends Controller
{
    public function document($id)
    {
        $document = Document::with([
            'categorie',
            'dossier',
            'utilisateur',
            'versions',
            'validations.utilisateur',
            'archivage'
        ])->find($id);

        if (!$document) {
            return response()->json([
                'message' => 'Document introuvable'
            ], 404);
        }

        return response()->json([
            'historique' => $document
        ]);
    }
}