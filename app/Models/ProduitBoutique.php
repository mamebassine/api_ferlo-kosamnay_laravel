<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProduitBoutique extends Model
{
    use HasFactory;

    // Les champs qui peuvent être remplis en masse
    protected $fillable = ['produit_id', 'boutique_id', 'quantite'];

    // Pas besoin de relation explicite ici car c'est une table pivot entre produits et boutiques
}
