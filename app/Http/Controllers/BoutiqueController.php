<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Boutique;
use Illuminate\Http\Request;

class BoutiqueController extends Controller
{
    // GET : Liste toutes les boutiques
    public function index()
    {
        $boutiques = Boutique::with('region', 'produits', 'user')->get();
        return response()->json(['boutiques' => $boutiques], 200);
    }

    // POST : Crée une nouvelle boutique
    public function store(Request $request)
    {

// Validation des données envoyées dans la requête
        $validatedData = $request->validate([
            'nom' => ['required', 'string', 'max:15', 'regex:/^[a-zA-Z\s]*$/'],
            'adresse' => ['required', 'string', 'max:15', 'regex:/^[a-zA-Z\s]*$/'],
            'telephone' => ['required', 'regex:/^\+?[0-9]{8,20}$/'], // Numéro avec 8 à 20 chiffres, peut inclure '+'
            'region_id' => 'required|exists:regions,id', // Doit correspondre à un ID dans la table regions
            'user_id' => 'nullable|exists:users,id', // Peut être null, sinon doit exister dans la table users
        ]);

        // Création de la boutique
        $boutique = Boutique::create($validatedData);

        return response()->json([
            'message' => 'Boutique créée avec succès.',
            'boutique' => $boutique
        ], 201);
    }

    // GET : Affiche une boutique spécifique
    public function show($id)
    {
        $boutique = Boutique::with('region', 'produits')->findOrFail($id);
        return response()->json(['boutique' => $boutique], 200);
    }

    // PUT/PATCH : Met à jour une boutique
    public function update(Request $request, $id)
    {
        $boutique = Boutique::findOrFail($id);

        // Validation des données pour la mise à jour
        $validatedData = $request->validate([
            'nom' => ['required', 'string', 'max:15', 'regex:/^[a-zA-Z\s]*$/'],
            'adresse' => ['required', 'string', 'max:15', 'regex:/^[a-zA-Z\s]*$/'],
            'telephone' => ['required', 'regex:/^\+?[0-9]{8,20}$/'],
            'region_id' => 'nullable|exists:regions,id',
            'user_id' => 'nullable|exists:users,id',
        ]);

        // Mise à jour de la boutique
        $boutique->update($validatedData);

        return response()->json([
            'message' => 'Boutique mise à jour avec succès.',
            'boutique' => $boutique
        ], 200);
    }

    // DELETE : Supprime une boutique
    public function destroy($id)
    {
        $boutique = Boutique::findOrFail($id);
        $boutique->delete();

        return response()->json(['message' => 'Boutique supprimée avec succès'], 200);
    }
}
