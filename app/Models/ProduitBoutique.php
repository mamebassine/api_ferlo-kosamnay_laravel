<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProduitBoutique extends Model
{
    use HasFactory;

    protected $table = 'produit_boutique';  // Nom de la table
    protected $fillable = ['produit_id', 'boutique_id', 'quantite'];

    // Relation avec Produit
    public function produit()
    {
        return $this->belongsTo(Produit::class, 'produit_id');
    }

    // Relation avec Boutique
    public function boutique()
    {
        return $this->belongsTo(Boutique::class, 'boutique_id');
    }

    // Relation Many-to-Many avec LigneCommande via une table pivot "commande_produitboutique"
    public function ligneCommandes()
    {
        return $this->belongsToMany(LigneCommande::class, 'commandes', 'produit_boutique_id', 'ligne_commande_id')
            ->withPivot('quantite', 'montant') // Champs supplémentaires dans la table pivot
            ->withTimestamps();
    }
}

