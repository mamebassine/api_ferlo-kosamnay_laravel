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
            $table->id();
            $table->foreignId('produit_id')->constrained('produits')->onDelete('cascade'); // Lien vers le produit
            $table->foreignId('commande_id')->constrained('commandes')->onDelete('cascade'); // Lien vers la commande
            $table->integer('quantite_totale'); // Quantité totale de ce produit dans la commande
            $table->decimal('prix_total', 10, 2); // Prix total pour cette ligne de commande
            $table->timestamps();
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
