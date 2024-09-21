<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
    use HasFactory;

    protected $fillable = [
        'image',
        'description',
        'prix',
        'quantite',
        'categorie_id',
    ];

    public function category()
    {
        return $this->belongsTo(Categorie::class);
    }

    public function lignesCommandes()
    {
        return $this->hasMany(LigneCommande::class);
    }
}
