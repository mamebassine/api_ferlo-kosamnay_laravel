<?php

namespace App\Http\Controllers;

use App\Models\adresse;
use Illuminate\Http\Request;

class adresseController extends Controller
{
    // Liste toutes les régions
    public function index()
    {
        $adresses = adresse::all();
        return response()->json($adresses, 200); // Spécification du code 200
    }

    // Affiche une région spécifique
    public function show($id)
    {
        $adresse = adresse::find($id);
        
        if (!$adresse) {
            return response()->json(['message' => 'Région non trouvée'], 404);
        }

        return response()->json($adresse, 200);
    }

    // Crée une nouvelle région
    public function store(Request $request)
    {
        // Validation des données
        $request->validate([
            'nom' => 'required|string|max:255',
        ]);

        // Création de la région
        $adresse = adresse::create($request->only('nom'));

        return response()->json($adresse, 201);
    }

    // Met à jour une région existante
    public function update(Request $request, $id)
    {
        // Validation des données
        $request->validate([
            'nom' => 'required|string|max:255',
        ]);

        $adresse = adresse::find($id);
        
        if (!$adresse) {
            return response()->json(['message' => 'Région non trouvée'], 404);
        }

        // Mise à jour du nom de la région
        $adresse->update($request->only('nom'));

        return response()->json($adresse, 200);
    }

    // Supprime une région
    public function destroy($id)
    {
        $adresse = adresse::find($id);

        if (!$adresse) {
            return response()->json(['message' => 'Région non trouvée'], 404);
        }

        $adresse->delete();

        return response()->json(['message' => 'Région supprimée avec succès'], 200);
    }
}
