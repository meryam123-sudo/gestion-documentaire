<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Validation;

class Utilisateur extends Authenticatable
{
    use HasApiTokens;
    protected $table = 'utilisateurs';

    protected $primaryKey = 'idUtilisateur';

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'mot_de_passe',
        'actif',
        'idRole',
    ];

    protected $hidden = [
        'mot_de_passe',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class, 'idRole', 'idRole');
    }

    public function documents()
    {
        return $this->hasMany(Document::class, 'idUtilisateur', 'idUtilisateur');
    }
    public function validations()
{
    return $this->hasMany(Validation::class, 'idUtilisateur', 'idUtilisateur');
}
}