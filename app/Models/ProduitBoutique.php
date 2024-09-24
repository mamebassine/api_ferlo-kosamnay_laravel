<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProduitBoutique extends Model
{
    use HasFactory;

    protected $table = 'produit_boutique';  // Nom de la table
    protected $fillable = ['produit_id', 'boutique_id', 'quantite'];

    public function produit()
    {
        return $this->belongsTo(Produit::class, 'produit_id');
    }

    public function boutique()
    {
        return $this->belongsTo(Boutique::class, 'boutique_id');
    }
}
