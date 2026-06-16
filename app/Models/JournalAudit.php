<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JournalAudit extends Model
{
    protected $table = 'journal_audits';
    protected $primaryKey = 'idAudit';

    protected $fillable = [
        'idUtilisateur',
        'action',
        'details',
        'adresseIp',
        'dateAction',
    ];

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'idUtilisateur', 'idUtilisateur');
    }
}