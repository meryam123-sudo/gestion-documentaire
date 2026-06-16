<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Archivage extends Model
{
    protected $primaryKey = 'idArchivage';

    protected $fillable = [
        'idDocument',
        'idUtilisateur',
        'emplacementArchive',
        'motifArchivage',
        'dateArchivage',
    ];

    public function document()
    {
        return $this->belongsTo(Document::class, 'idDocument', 'idDocument');
    }

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'idUtilisateur', 'idUtilisateur');
    }
}