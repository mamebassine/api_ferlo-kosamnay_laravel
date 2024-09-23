<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ligne_commandes', function (Blueprint $table) {
            $table->id();  // Clé primaire
            $table->foreignId('produit_id')->constrained('produits');  // Clé étrangère vers la table "produits"
            $table->foreignId('user_id')->constrained('users');  // Clé étrangère vers la table "users"
            $table->date('date');  // Date de la commande
            $table->enum('statut', ['en attente', 'livré', 'en cours de traitement', 'annulé']);  // Statut de la commande
            $table->integer('quantite_totale');  // Quantité totale commandée
            $table->decimal('prix_totale', 8, 2);  // Prix total de la commande
            $table->timestamps();  // Colonnes de date
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ligne_commandes');
    }
};
