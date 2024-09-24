<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
    use HasFactory;

    // Les champs qui peuvent être remplis en masse
    protected $fillable = ['categorie_id', 'image', 'description', 'prix', 'quantite', 'reference'];

    /**
     * Relation avec le modèle Categorie.
     * Un produit appartient à une catégorie.
     */
    public function categorie()
    {
        return $this->belongsTo(Categorie::class); // belongsTo: Chaque produit appartient à une catégorie.
    }

    /**
     * Relation avec le modèle Boutique.
     * Un produit peut être disponible dans plusieurs boutiques.
     */
    public function boutiques()
    {
        return $this->belongsToMany(Boutique::class, 'produit_boutique')
                    ->withPivot('quantite') // Définit la quantité de produit dans chaque boutique via la table pivot
                    ->withTimestamps(); // Gère les colonnes created_at et updated_at de la table pivot.
    }
}
