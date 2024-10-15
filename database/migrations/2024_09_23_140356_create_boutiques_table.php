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
        // Crée la table "boutiques" avec les colonnes id, nom, adresse, etc.
    Schema::create('boutiques', function (Blueprint $table) {
        $table->id();  // Clé primaire
        $table->string('nom');  // Nom de la boutique
        $table->string('adresse');  // adresse de la boutique
        $table->string('telephone');  // Téléphone de la boutique
        $table->foreignId('region_id')->constrained('regions')->onDelete('cascade');  // Clé étrangère vers la table "adresses" avec suppression en cascade
        $table->timestamps();  // Colonnes de date
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('boutiques');
    }
};
