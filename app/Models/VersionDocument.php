<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VersionDocument extends Model
{
    protected $table = 'version_documents';
    protected $primaryKey = 'idVersion';

    protected $fillable = [
        'idDocument',
        'numeroVersion',
        'fichier',
        'commentaire',
    ];

    public function document()
    {
        return $this->belongsTo(Document::class, 'idDocument', 'idDocument');
    }
}