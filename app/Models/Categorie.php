<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{
    use HasFactory;

    // Les champs qui peuvent être remplis en masse (via $fillable)
    protected $fillable = ['nom_complet', 'description'];

    /**
     * Relation avec le modèle Produit.
     * Une catégorie peut avoir plusieurs produits.
     */
    public function produits()
    {
        return $this->hasMany(Produit::class); // hasMany: Une catégorie possède plusieurs produits.
    }
}
