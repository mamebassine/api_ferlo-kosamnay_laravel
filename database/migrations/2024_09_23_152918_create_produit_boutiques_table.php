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
// Crée la table pivot "produit_boutique" avec les colonnes pour relier produits et boutiques
Schema::create('produit_boutique', function (Blueprint $table) {
    $table->id();  // Clé primaire
    $table->foreignId('produit_id')->constrained('produits');  // Clé étrangère vers la table "produits"
    $table->foreignId('boutique_id')->constrained('boutiques');  // Clé étrangère vers la table "boutiques"
    $table->integer('quantite');  // Quantité de produits disponibles dans la boutique
    $table->timestamps();  // Colonnes de date
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produit_boutique');
    }
};
