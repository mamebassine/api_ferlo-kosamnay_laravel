<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    use HasFactory;

    // Les champs qui peuvent être remplis en masse
    protected $fillable = ['ligne_commande_id', 'montant', 'date', 'type'];

    /**
     * Relation avec le modèle LigneCommande.
     * Un paiement est lié à une commande.
     */
    public function commande()
    {
        return $this->belongsTo(LigneCommande::class, 'ligne_commande_id'); // belongsTo: Un paiement est lié à une commande.
    }
}
