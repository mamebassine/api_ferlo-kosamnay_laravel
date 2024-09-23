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
     * Une région peut avoir plusieurs boutiques.
     */
    public function boutiques()
    {
        return $this->hasMany(Boutique::class); // hasMany: Une région possède plusieurs boutiques.
    }
}
