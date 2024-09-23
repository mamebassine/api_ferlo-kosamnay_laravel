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
            $table->id();  // Clé primaire pour la table ligne_commandes
            $table->foreignId('produit_boutique_id')  // Clé étrangère vers la table "produit_boutique"
                ->constrained('produit_boutique')  // Définit la relation avec la table produit_boutique
                ->onDelete('cascade');  // Supprime les lignes correspondantes en cas de suppression dans produit_boutique
            
            $table->foreignId('user_id')  // Clé étrangère vers la table "users"
                ->constrained('users')  // Définit la relation avec la table users
                ->onDelete('cascade');  // Supprime les lignes correspondantes en cas de suppression dans users
            
            $table->date('date');  // Date de la commande
            $table->enum('statut', ['en attente', 'livré', 'en cours de traitement', 'annulé']);  // Statut de la commande
            $table->integer('quantite_totale');  // Quantité totale commandée
            $table->decimal('prix_totale', 8, 2);  // Prix total de la commande
            $table->timestamps();  // Ajoute les colonnes created_at et updated_at

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
