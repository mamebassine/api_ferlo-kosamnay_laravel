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
        Schema::table('boutiques', function (Blueprint $table) {
            // Ajoute une colonne user_id qui sera une clé étrangère
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('boutiques', function (Blueprint $table) {
            // Supprime la clé étrangère et la colonne user_id
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
