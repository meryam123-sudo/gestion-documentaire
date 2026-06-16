<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Validation extends Model
{
    protected $primaryKey = 'idValidation';

    protected $fillable = [
        'idDocument',
        'idUtilisateur',
        'decision',
        'commentaire',
        'dateValidation'
    ];

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    public function document()
    {
        return $this->belongsTo(Document::class, 'idDocument', 'idDocument');
    }

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'idUtilisateur', 'idUtilisateur');
    }
}