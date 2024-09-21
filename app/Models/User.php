<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Les attributs qui peuvent être attribués en masse.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nom_complet',
        'telephone',
        'adresse',
        'email',
        'password',
        'role',
    ];

    /**
     * Les attributs qui doivent être cachés lors de la sérialisation.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Les attributs qui doivent être convertis.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relations
    public function commandes()
    {
        return $this->hasMany(Commande::class); // Relation avec les commandes
    }

    public function region()
    {
        return $this->belongsTo(Region::class); // Relation avec la région
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class); // Relation avec les notifications
    }

    public function produits()
    {
        return $this->hasMany(Produit::class); // Relation avec les produits
    }
}
