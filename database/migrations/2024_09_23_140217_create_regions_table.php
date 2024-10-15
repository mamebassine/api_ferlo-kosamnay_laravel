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
        // Crée la table "adresses" avec les colonnes id et nom
    Schema::create('regions', function (Blueprint $table) {
        $table->id();  // Clé primaire
        $table->string('nom')->unique();  // Nom de la région
        $table->timestamps();  // Colonnes de date
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adresses');
    }
};
