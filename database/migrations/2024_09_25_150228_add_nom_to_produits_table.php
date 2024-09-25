<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Exécute la migration.
     */
    public function up(): void
    {
        Schema::table('produits', function (Blueprint $table) {
            // Ajoute la colonne 'nom' après la colonne 'reference'
            $table->string('nom')->after('reference');
        });
    }

    /**
     * Annule la migration.
     */
    public function down(): void
    {
        Schema::table('produits', function (Blueprint $table) {
            // Supprime la colonne 'nom' lors du rollback
            $table->dropColumn('nom');
        });
    }
};
