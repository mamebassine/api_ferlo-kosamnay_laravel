<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    // Les champs qui peuvent être remplis en masse
    protected $fillable = ['nom_complet', 'telephone', 'adresse', 'email', 'password', 'role'];

    // Méthodes requises par l'interface JWTSubject
    public function getJWTIdentifier()
    {
        return $this->getKey(); // Retourne l'identifiant unique de l'utilisateur
    }

    public function getJWTCustomClaims()
    {
        return []; // Vous pouvez ajouter des données personnalisées ici
    }

    /**
     * Relation avec le modèle LigneCommande.
     * Un utilisateur peut passer plusieurs commandes.
     */
    public function commandes()
    {
        return $this->hasMany(LigneCommande::class); // Un utilisateur peut avoir plusieurs commandes.
    }

    /**
     * Relation avec le modèle Notification.
     * Un utilisateur peut recevoir plusieurs notifications.
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class); // Un utilisateur peut recevoir plusieurs notifications.
    }
}
