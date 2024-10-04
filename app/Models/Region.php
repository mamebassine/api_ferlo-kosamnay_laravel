<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    use HasFactory;

    // Les champs qui peuvent être remplis en masse
    protected $fillable = ['nom'];

     /**
     * Relation avec le modèle Boutique.
     * Une région peut avoir une boutique (0..1).
     */
    public function boutique()
    {
        return $this->hasOne(Boutique::class); // Une région peut avoir une boutique.
    }
}
