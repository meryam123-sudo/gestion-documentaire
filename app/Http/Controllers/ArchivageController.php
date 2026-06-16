<?php

namespace App\Http\Controllers;

use App\Models\Archivage;
use App\Models\Document;
use App\Models\JournalAudit;
use Illuminate\Http\Request;

class ArchivageController extends Controller
{
    public function index()
    {
        return response()->json(
            Archivage::with(['document', 'utilisateur'])->get()
        );
    }

    public function documentsValides()
    {
        return response()->json(
            Document::with(['categorie', 'dossier', 'utilisateur'])
                ->where('statut', 'validé')
                ->get()
        );
    }

    public function archiver(Request $request, $id)
    {
        $request->validate([
            'idUtilisateur' => 'required|exists:utilisateurs,idUtilisateur',
            'emplacementArchive' => 'required|string|max:255',
            'motifArchivage' => 'nullable|string',
        ]);

        $document = Document::find($id);

        if (!$document) {
            return response()->json(['message' => 'Document introuvable'], 404);
        }

        if ($document->statut !== 'validé') {
            return response()->json([
                'message' => 'Seuls les documents validés peuvent être archivés'
            ], 400);
        }

        $existe = Archivage::where('idDocument', $id)->first();

        if ($existe) {
            return response()->json([
                'message' => 'Ce document est déjà archivé'
            ], 400);
        }

        $archivage = Archivage::create([
            'idDocument' => $document->idDocument,
            'idUtilisateur' => $request->idUtilisateur,
            'emplacementArchive' => $request->emplacementArchive,
            'motifArchivage' => $request->motifArchivage,
        ]);

        $document->update([
            'statut' => 'archivé'
        ]);
        JournalAudit::create([
    'idUtilisateur' => $request->idUtilisateur,
    'action' => 'ARCHIVAGE_DOCUMENT',
    'details' => 'Document ID ' . $document->idDocument . ' archivé à : ' . $request->emplacementArchive,
    'adresseIp' => $request->ip(),
]);

        return response()->json([
            'message' => 'Document archivé avec succès',
            'document' => $document,
            'archivage' => $archivage
        ], 201);
    }

    public function show($id)
    {
        $archivage = Archivage::with(['document', 'utilisateur'])
            ->where('idDocument', $id)
            ->first();

        if (!$archivage) {
            return response()->json([
                'message' => 'Archivage introuvable pour ce document'
            ], 404);
        }

        return response()->json($archivage);
    }
}