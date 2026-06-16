<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategorieDocument extends Model
{
    protected $table = 'categorie_documents';

    protected $primaryKey = 'idCategorie';

    protected $fillable = [
        'nomCategorie',
        'description',
    ];

    public function documents()
    {
        return $this->hasMany(Document::class, 'idCategorie', 'idCategorie');
    }
}