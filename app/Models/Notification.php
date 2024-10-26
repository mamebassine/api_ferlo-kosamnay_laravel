<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', // Ajoutez l'utilisateur comme champ remplissable
        'objet',
        'description',
        'lue',
    ];
    protected $casts = [
        'lue' => 'boolean',
    ];
    
    
    public function user()
    {
        return $this->belongsTo(User::class); // Lien vers l'utilisateur
    }
}
