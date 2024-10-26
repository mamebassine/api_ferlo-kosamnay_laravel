<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    use HasFactory;

    protected $fillable = [
        'produit_boutique_id',
        'ligne_commande_id',
        'quantite',
        'montant',
    ];

    // Dans le modèle Commande.php
public function ligneCommande()
{
    return $this->belongsTo(LigneCommande::class);
}

public function produitBoutique()
{
    return $this->belongsTo(ProduitBoutique::class);
}

}
