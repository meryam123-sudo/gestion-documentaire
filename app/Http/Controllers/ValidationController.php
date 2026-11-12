<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Validation;
use App\Models\JournalAudit;
use App\Models\Notification;
use Illuminate\Http\Request;

class ValidationController extends Controller
{
    private function verifierRoleValidation()
    {
        $role = auth()->user()->role->nomRole;

        if (!in_array($role, [
            'Administrateur Principal',
            'Responsable de validation'
        ])) {
            return response()->json([
                'message' => 'Accès refusé'
            ], 403);
        }

        return null;
    }

    public function documentsSoumis()
    {
        $autorisation = $this->verifierRoleValidation();

        if ($autorisation) {
            return $autorisation;
        }

        $documents = Document::with(['categorie', 'dossier', 'utilisateur'])
            ->where('statut', 'soumis')
            ->get();

        return response()->json($documents);
    }

    public function valider(Request $request, $id)
    {
        $autorisation = $this->verifierRoleValidation();

        if ($autorisation) {
            return $autorisation;
        }

        $request->validate([
            'idUtilisateur' => 'required|exists:utilisateurs,idUtilisateur',
            'commentaire' => 'nullable|string',
        ]);

        $document = Document::find($id);

        if (!$document) {
            return response()->json(['message' => 'Document introuvable'], 404);
        }

        if ($document->statut !== 'soumis') {
            return response()->json([
                'message' => 'Seuls les documents soumis peuvent être validés'
            ], 400);
        }

        $document->update([
            'statut' => 'validé'
        ]);

        $validation = Validation::create([
            'idDocument' => $document->idDocument,
            'idUtilisateur' => $request->idUtilisateur,
            'decision' => 'validé',
            'commentaire' => $request->commentaire,
        ]);

        JournalAudit::create([
            'idUtilisateur' => $request->idUtilisateur,
            'action' => 'VALIDATION_DOCUMENT',
            'details' => 'Document ID ' . $document->idDocument . ' validé',
            'adresseIp' => $request->ip(),
        ]);

        Notification::create([
            'idUtilisateur' => $document->idUtilisateur,
            'idDocument' => $document->idDocument,
            'typeNotification' => 'VALIDATION',
            'message' => 'Votre document "' . $document->titre . '" a été validé.',
        ]);

        return response()->json([
            'message' => 'Document validé avec succès',
            'document' => $document,
            'validation' => $validation
        ]);
    }

    public function rejeter(Request $request, $id)
    {
        $autorisation = $this->verifierRoleValidation();

        if ($autorisation) {
            return $autorisation;
        }

        $request->validate([
            'idUtilisateur' => 'required|exists:utilisateurs,idUtilisateur',
            'commentaire' => 'required|string',
        ]);

        $document = Document::find($id);

        if (!$document) {
            return response()->json(['message' => 'Document introuvable'], 404);
        }

        if ($document->statut !== 'soumis') {
            return response()->json([
                'message' => 'Seuls les documents soumis peuvent être rejetés'
            ], 400);
        }

        $document->update([
            'statut' => 'rejeté'
        ]);

        $validation = Validation::create([
            'idDocument' => $document->idDocument,
            'idUtilisateur' => $request->idUtilisateur,
            'decision' => 'rejeté',
            'commentaire' => $request->commentaire,
        ]);

        JournalAudit::create([
            'idUtilisateur' => $request->idUtilisateur,
            'action' => 'REJET_DOCUMENT',
            'details' => 'Document ID ' . $document->idDocument . ' rejeté',
            'adresseIp' => $request->ip(),
        ]);

        Notification::create([
            'idUtilisateur' => $document->idUtilisateur,
            'idDocument' => $document->idDocument,
            'typeNotification' => 'REJET',
            'message' => 'Votre document "' . $document->titre . '" a été rejeté. Commentaire : ' . $request->commentaire,
        ]);

        return response()->json([
            'message' => 'Document rejeté avec succès',
            'document' => $document,
            'validation' => $validation
        ]);
    }

    public function demanderCorrection(Request $request, $id)
    {
        $autorisation = $this->verifierRoleValidation();

        if ($autorisation) {
            return $autorisation;
        }

        $request->validate([
            'idUtilisateur' => 'required|exists:utilisateurs,idUtilisateur',
            'commentaire' => 'required|string',
        ]);

        $document = Document::find($id);

        if (!$document) {
            return response()->json(['message' => 'Document introuvable'], 404);
        }

        if ($document->statut !== 'soumis') {
            return response()->json([
                'message' => 'Seuls les documents soumis peuvent être envoyés en correction'
            ], 400);
        }

        $document->update([
            'statut' => 'à_corriger'
        ]);

        $validation = Validation::create([
            'idDocument' => $document->idDocument,
            'idUtilisateur' => $request->idUtilisateur,
            'decision' => 'à_corriger',
            'commentaire' => $request->commentaire,
        ]);

        JournalAudit::create([
            'idUtilisateur' => $request->idUtilisateur,
            'action' => 'CORRECTION_DOCUMENT',
            'details' => 'Correction demandée pour le document ID ' . $document->idDocument,
            'adresseIp' => $request->ip(),
        ]);

        Notification::create([
            'idUtilisateur' => $document->idUtilisateur,
            'idDocument' => $document->idDocument,
            'typeNotification' => 'CORRECTION',
            'message' => 'Une correction est demandée pour votre document "' . $document->titre . '". Commentaire : ' . $request->commentaire,
        ]);

        return response()->json([
            'message' => 'Demande de correction envoyée avec succès',
            'document' => $document,
            'validation' => $validation
        ]);
    }
}