<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Utilisateur;
use App\Models\Notification;
use App\Models\JournalAudit;

class DashboardController extends Controller
{
    public function stats()
    {
        return response()->json([
            'totalDocuments' => Document::count(),
            'documentsBrouillon' => Document::where('statut', 'brouillon')->count(),
            'documentsSoumis' => Document::where('statut', 'soumis')->count(),
            'documentsValides' => Document::where('statut', 'validé')->count(),
            'documentsRejetes' => Document::where('statut', 'rejeté')->count(),
            'documentsArchives' => Document::where('statut', 'archivé')->count(),

            'totalUtilisateurs' => Utilisateur::count(),
            'notificationsNonLues' => Notification::where('lue', false)->count(),

            'documentsRecents' => Document::with(['categorie', 'utilisateur'])
                ->latest()
                ->take(5)
                ->get(),

            'activitesRecentes' => JournalAudit::with('utilisateur')
                ->latest('dateAction')
                ->take(5)
                ->get(),
        ]);
    }
}