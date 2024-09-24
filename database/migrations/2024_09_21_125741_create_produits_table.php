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
        // Crée la table "produits" avec les colonnes id, image, description, etc.
    Schema::create('produits', function (Blueprint $table) {
        $table->id();  // Clé primaire
        $table->foreignId('categorie_id')->constrained('categories');  // Clé étrangère vers la table "categories"
        $table->string('image');  // Colonne pour stocker l'image du produit
        $table->text('description');  // Colonne pour la description du produit
        $table->decimal('prix', 8, 2);  // Colonne pour le prix du produit, format décimal
        $table->integer('quantite');  // Colonne pour stocker la quantité disponible
        $table->string('reference')->unique();  // Référence unique du produit
        $table->timestamps();  // Colonnes de date
    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Suppression de la table 'produits' si nécessaire
        Schema::dropIfExists('produits');
    }
};
