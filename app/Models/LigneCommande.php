<?php

// app/Models/LigneCommande.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LigneCommande extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date',
        'statut',
        'quantite_totale',
        'prix_totale',
    ];

    // Relation avec le modèle User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
        // Relation Many-to-Many avec ProduitBoutique via la table pivot "commande_produitboutique"

  
    public function produitBoutiques()
    {
        return $this->belongsToMany(ProduitBoutique::class, 'commandes', 'ligne_commande_id', 'produit_boutique_id')
            ->withPivot('quantite', 'montant'); // Include any pivot table fields
    }
    
}
