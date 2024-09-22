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
        // Création de la table 'produits'
        Schema::create('produits', function (Blueprint $table) {
            $table->id();
            $table->string('image')->nullable(); // Image du produit (chemin d'accès)
            $table->string('nom'); 
            $table->text('description')->nullable(); // Description du produit
            $table->decimal('prix', 8, 2); // Prix du produit avec deux décimales
            $table->integer('quantite'); // Quantité disponible en stock
            $table->foreignId('categorie_id')->constrained('categories')->onDelete('cascade'); // Clé étrangère vers 'categories'
            $table->timestamps();
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
