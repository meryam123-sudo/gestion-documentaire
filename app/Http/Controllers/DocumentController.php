<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\VersionDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
 public function index(Request $request)
{
    $query = Document::with([
        'categorie',
        'dossier',
        'utilisateur',
        'versions'
    ]);

    $utilisateur = $request->user();

    if ($utilisateur && $utilisateur->role) {
        $role = $utilisateur->role->nomRole;

        if ($role === 'Gestionnaire documentaire') {
            $query->where('idUtilisateur', $utilisateur->idUtilisateur);
        }

        if ($role === 'Archiviste') {
            $query->whereIn('statut', ['validé', 'archivé']);
        }

        if ($role === 'Consultant autorisé') {
            $query->whereIn('statut', ['validé', 'archivé']);
        }
    }

    if ($request->titre) {
        $query->where('titre', 'like', '%' . $request->titre . '%');
    }

    if ($request->reference) {
        $query->where('reference', 'like', '%' . $request->reference . '%');
    }

    if ($request->statut) {
        $query->where('statut', $request->statut);
    }

    if ($request->niveauConfidentialite) {
        $query->where('niveauConfidentialite', $request->niveauConfidentialite);
    }

    if ($request->idCategorie) {
        $query->where('idCategorie', $request->idCategorie);
    }

    $documents = $query->paginate(10);

    $documents->getCollection()->transform(function ($document) {
        if (
            $document->dateExpiration &&
            now()->toDateString() > $document->dateExpiration &&
            $document->statut !== 'archivé'
        ) {
            $document->statutAffiche = 'expiré';
        } else {
            $document->statutAffiche = $document->statut;
        }

        return $document;
    });

    return response()->json($documents);
}

    public function store(Request $request)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'reference' => 'required|string|max:255|unique:documents,reference',
            'description' => 'nullable|string',
            'typeDocument' => 'required|string|max:255',
            'niveauConfidentialite' => 'required|string|max:255',
            'idCategorie' => 'required|exists:categorie_documents,idCategorie',
            'idDossier' => 'nullable|exists:dossiers,idDossier',
            'idUtilisateur' => 'required|exists:utilisateurs,idUtilisateur',
            'fichier' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
            'dateEmission' => 'nullable|date',
            'dateExpiration' => 'nullable|date|after_or_equal:dateEmission',
        ]);

        $cheminFichier = $request->file('fichier')->store('documents', 'public');

        $document = Document::create([
            'titre' => $request->titre,
            'reference' => $request->reference,
            'description' => $request->description,
            'typeDocument' => $request->typeDocument,
            'niveauConfidentialite' => $request->niveauConfidentialite,
            'fichier' => $cheminFichier,
            'statut' => 'brouillon',
            'idCategorie' => $request->idCategorie,
            'idDossier' => $request->idDossier,
            'idUtilisateur' => $request->idUtilisateur,
            'dateEmission' => $request->dateEmission,
            'dateExpiration' => $request->dateExpiration,
        ]);

        VersionDocument::create([
            'idDocument' => $document->idDocument,
            'numeroVersion' => '1.0',
            'fichier' => $cheminFichier,
            'commentaire' => 'Version initiale',
        ]);

        return response()->json([
            'message' => 'Document déposé avec succès',
            'document' => $document->load([
                'categorie',
                'dossier',
                'utilisateur',
                'versions'
            ])
        ], 201);
    }

    public function show($id)
    {
        $document = Document::with([
            'categorie',
            'dossier',
            'utilisateur',
            'versions'
        ])->find($id);

        if (!$document) {
            return response()->json([
                'message' => 'Document introuvable'
            ], 404);
        }

        return response()->json($document);
    }

    public function update(Request $request, $id)
    {
        $document = Document::find($id);

        if (!$document) {
            return response()->json([
                'message' => 'Document introuvable'
            ], 404);
        }

        if ($document->statut !== 'brouillon') {
            return response()->json([
                'message' => 'Seuls les documents en brouillon peuvent être modifiés'
            ], 400);
        }

        $request->validate([
            'titre' => 'required|string|max:255',
            'reference' => 'required|string|max:255|unique:documents,reference,' . $id . ',idDocument',
            'description' => 'nullable|string',
            'typeDocument' => 'required|string|max:255',
            'niveauConfidentialite' => 'required|string|max:255',
            'idCategorie' => 'required|exists:categorie_documents,idCategorie',
            'idDossier' => 'nullable|exists:dossiers,idDossier',
        ]);

        $document->update($request->only([
            'titre',
            'reference',
            'description',
            'typeDocument',
            'niveauConfidentialite',
            'idCategorie',
            'idDossier',
        ]));

        return response()->json([
            'message' => 'Document modifié avec succès',
            'document' => $document->load([
                'categorie',
                'dossier',
                'utilisateur',
                'versions'
            ])
        ]);
    }

    public function destroy($id)
    {
        $document = Document::find($id);

        if (!$document) {
            return response()->json([
                'message' => 'Document introuvable'
            ], 404);
        }

        if ($document->statut !== 'brouillon') {
            return response()->json([
                'message' => 'Seuls les documents en brouillon peuvent être supprimés'
            ], 403);
        }

        $utilisateur = auth()->user();
        $role = $utilisateur->role->nomRole;

        if (
            $document->idUtilisateur !== $utilisateur->idUtilisateur &&
            $role !== 'Administrateur Principal'
        ) {
            return response()->json([
                'message' => 'Accès refusé'
            ], 403);
        }

        $document->delete();

        return response()->json([
            'message' => 'Document supprimé'
        ]);
    }

    public function soumettre($id)
    {
        $document = Document::find($id);

        if (!$document) {
            return response()->json([
                'message' => 'Document introuvable'
            ], 404);
        }

        if ($document->statut !== 'brouillon') {
            return response()->json([
                'message' => 'Seuls les documents en brouillon peuvent être soumis'
            ], 400);
        }

        $document->update([
            'statut' => 'soumis'
        ]);

        return response()->json([
            'message' => 'Document soumis à validation avec succès',
            'document' => $document->load([
                'categorie',
                'dossier',
                'utilisateur',
                'versions'
            ])
        ]);
    }

    public function telecharger($id)
{
    $document = Document::find($id);

    if (!$document) {
        return response()->json([
            'message' => 'Document introuvable'
        ], 404);
    }

    $path = storage_path('app/public/' . $document->fichier);

    if (!file_exists($path)) {
        return response()->json([
            'message' => 'Fichier introuvable'
        ], 404);
    }

    return response()->download(
        $path,
        basename($document->fichier)
    );
}
}