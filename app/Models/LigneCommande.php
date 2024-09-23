<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LigneCommande extends Model
{
    use HasFactory;

    // Les champs qui peuvent être remplis en masse
    protected $fillable = ['produit_id', 'user_id', 'date', 'statut', 'quantite_totale', 'prix_total'];

    /**
     * Relation avec le modèle User.
     * Une commande est passée par un utilisateur.
     */
    public function user()
    {
        return $this->belongsTo(User::class); // belongsTo: Une commande appartient à un utilisateur.
    }

    /**
     * Relation avec le modèle Produit.
     * Une commande contient un produit.
     */
    public function produit()
    {
        return $this->belongsTo(Produit::class); // belongsTo: Chaque commande contient un produit.
    }

    /**
     * Relation avec le modèle Paiement.
     * Une commande peut avoir un paiement associé.
     */
    public function paiement()
    {
        return $this->hasOne(Paiement::class); // hasOne: Une commande peut avoir un seul paiement.
    }
}
