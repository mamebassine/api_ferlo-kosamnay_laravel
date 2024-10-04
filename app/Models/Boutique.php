<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Boutique extends Model
{
    use HasFactory;

    // Les champs qui peuvent être remplis en masse
    protected $fillable = ['nom', 'adresse', 'telephone', 'adresse_id', 'user_id'];

    /**
     * Relation avec le modèle adresse.
     * Une boutique appartient à une région.
     */
    public function adresse()
    {
        return $this->belongsTo(region::class); // belongsTo: Une boutique appartient à une région.
    }

    /**
     * Relation avec le modèle user.
     * Une boutique appartient à une user.
     */
    public function user()
    {
        return $this->belongsTo(User::class); // belongsTo: Une boutique appartient à une user.
    }

    /**
     * Relation avec le modèle Produit.
     * Une boutique peut proposer plusieurs produits.
     */
    public function produits()
    {
        return $this->belongsToMany(Produit::class, 'produit_boutique')
                    ->withPivot('quantite') // Gère la quantité du produit dans la boutique.
                    ->withTimestamps(); // Ajoute des colonnes timestamps pour suivre les ajouts et mises à jour.
    }
}
