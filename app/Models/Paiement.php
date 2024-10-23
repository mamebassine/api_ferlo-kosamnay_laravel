<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    use HasFactory;

    protected $fillable = [
        'ligne_commande_id',
        'montant',
        'date',
        'type',
    ];

    // Relation avec LigneCommande
    public function ligneCommande()
    {
        return $this->belongsTo(LigneCommande::class);
    }
    
}
