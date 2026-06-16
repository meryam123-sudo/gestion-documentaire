<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Validation;
class Document extends Model
{
    protected $table = 'documents';
    protected $primaryKey = 'idDocument';

    protected $fillable = [
        'titre',
        'reference',
        'description',
        'typeDocument',
        'niveauConfidentialite',
        'fichier',
        'statut',
        'idCategorie',
        'idDossier',
        'idUtilisateur',
    ];

    public function categorie()
    {
        return $this->belongsTo(CategorieDocument::class, 'idCategorie', 'idCategorie');
    }

    public function dossier()
    {
        return $this->belongsTo(Dossier::class, 'idDossier', 'idDossier');
    }

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'idUtilisateur', 'idUtilisateur');
    }

    public function versions()
    {
        return $this->hasMany(VersionDocument::class, 'idDocument', 'idDocument');
    }
    public function validations()
{
    return $this->hasMany(Validation::class, 'idDocument', 'idDocument');
}
public function archivage()
{
    return $this->hasOne(Archivage::class, 'idDocument', 'idDocument');
}
}