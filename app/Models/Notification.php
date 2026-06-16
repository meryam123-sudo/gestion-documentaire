<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $primaryKey = 'idNotification';

    protected $fillable = [
        'idUtilisateur',
        'idDocument',
        'typeNotification',
        'message',
        'lue',
        'dateEnvoi',
    ];

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'idUtilisateur', 'idUtilisateur');
    }

    public function document()
    {
        return $this->belongsTo(Document::class, 'idDocument', 'idDocument');
    }
}