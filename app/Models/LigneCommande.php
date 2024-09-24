<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LigneCommande extends Model
{
    use HasFactory;

    // Définissez les attributs mass assignable
    protected $fillable = [
        'produit_boutique_id',
        'user_id',
        'date',
        'statut',
        'quantite_totale',
        'prix_totale',
    ];

    // Définissez la relation avec le modèle ProduitBoutique
    // public function produit()
    public function produitBoutique()

    {
        return $this->belongsTo(ProduitBoutique::class, 'produit_boutique_id');
    }

    // Définissez la relation avec le modèle User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}