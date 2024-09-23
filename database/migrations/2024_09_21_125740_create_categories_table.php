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
        // Création de la table 'categories'
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('nom_complet');
            $table->text('description');
            $table->timestamps();
        });
    
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Suppression de la table 'categories' si nécessaire
        Schema::dropIfExists('categories');
    }
};
