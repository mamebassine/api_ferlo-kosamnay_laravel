<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Les champs qui peuvent être remplis en masse
    protected $fillable = ['nom_complet', 'telephone', 'adresse', 'email', 'mot_passe', 'role'];

    /**
     * Relation avec le modèle LigneCommande.
     * Un utilisateur peut passer plusieurs commandes.
     */
    public function commandes()
    {
        return $this->hasMany(LigneCommande::class); // hasMany: Un utilisateur peut avoir plusieurs commandes.
    }

    /**
     * Relation avec le modèle Notification.
     * Un utilisateur peut recevoir plusieurs notifications.
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class); // hasMany: Un utilisateur peut recevoir plusieurs notifications.
    }
}
