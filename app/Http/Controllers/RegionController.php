<?php

namespace App\Http\Controllers;

use App\Models\Region;
use Illuminate\Http\Request;

class RegionController extends Controller
{
    // Liste toutes les régions
    public function index()
    {
        $regions = Region::all();
        return response()->json($regions, 200); // Spécification du code 200
    }

    // Affiche une région spécifique
    public function show($id)
    {
        $region = Region::find($id);
        
        if (!$region) {
            return response()->json(['message' => 'Région non trouvée'], 404);
        }

        return response()->json($region, 200);
    }

    // Crée une nouvelle région
    public function store(Request $request)
    {
        // Validation des données
        $request->validate([
            'nom' => 'required|string|max:255',
        ]);

        // Création de la région
        $region = Region::create($request->only('nom'));

        return response()->json($region, 201);
    }

    // Met à jour une région existante
    public function update(Request $request, $id)
    {
        // Validation des données
        $request->validate([
            'nom' => 'required|string|max:255',
        ]);

        $region = Region::find($id);
        
        if (!$region) {
            return response()->json(['message' => 'Région non trouvée'], 404);
        }

        // Mise à jour du nom de la région
        $region->update($request->only('nom'));

        return response()->json($region, 200);
    }

    // Supprime une région
    public function destroy($id)
    {
        $region = Region::find($id);

        if (!$region) {
            return response()->json(['message' => 'Région non trouvée'], 404);
        }

        $region->delete();

        return response()->json(['message' => 'Région supprimée avec succès'], 200);
    }
}
