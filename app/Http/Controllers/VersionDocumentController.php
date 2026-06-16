<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\VersionDocument;
use Illuminate\Http\Request;

class VersionDocumentController extends Controller
{
    public function index($id)
    {
        $document = Document::with('versions')->find($id);

        if (!$document) {
            return response()->json([
                'message' => 'Document introuvable'
            ], 404);
        }

        return response()->json([
            'document' => $document
        ]);
    }

    public function store(Request $request, $id)
    {
        $document = Document::find($id);

        if (!$document) {
            return response()->json([
                'message' => 'Document introuvable'
            ], 404);
        }

        $request->validate([
            'numeroVersion' => 'required|string|max:50',
            'commentaire' => 'nullable|string',
            'fichier' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
        ]);

        $cheminFichier = $request->file('fichier')->store('documents/versions', 'public');

        $version = VersionDocument::create([
            'idDocument' => $document->idDocument,
            'numeroVersion' => $request->numeroVersion,
            'fichier' => $cheminFichier,
            'commentaire' => $request->commentaire,
        ]);

        return response()->json([
            'message' => 'Nouvelle version ajoutée avec succès',
            'version' => $version
        ], 201);
    }
}