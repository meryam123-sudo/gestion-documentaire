<?php

namespace App\Http\Controllers;

use App\Models\JournalAudit;

class AuditController extends Controller
{
    public function index()
    {
        return response()->json(
            JournalAudit::with('utilisateur')
                ->orderBy('dateAction', 'desc')
                ->get()
        );
    }
}