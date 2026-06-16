<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dossier extends Model
{
    protected $table = 'dossiers';
    protected $primaryKey = 'idDossier';

    protected $fillable = [
        'nomDossier',
        'description',
    ];

    public function documents()
    {
        return $this->hasMany(Document::class, 'idDossier', 'idDossier');
    }
}