<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Création d'un utilisateur administrateur
        User::create([
            'nom_complet' => 'Administrateur',
            'telephone' => '771065156',
            'email' => 'mamebassine06@gmail.com',
            'password' => Hash::make('nessiba96'), // Hachage du mot de passe
            'role' => 'admin',
        ]);

       
    }
}
