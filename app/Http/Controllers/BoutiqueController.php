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
        // return response()->json(Boutique::with('adresse', 'produits')->get(), 200);
        $boutiques = Boutique::with('adresse', 'produits', 'user')->get();
        return response()->json(['boutiques' => $boutiques], 200);
    }

    // POST : Crée une nouvelle boutique
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nom' => 'required|string|max:255',
            'adresse' => 'required|string',
            'telephone' => 'required|string',
            'adresse_id' => 'required|exists:adresses,id',
            'user_id' => 'nullable|exists:adresses,id',

        ]);

        $boutique = Boutique::create($validatedData);

        // return response()->json($boutique, 201); 

        return response()->json([
            'message' => 'Boutique créée avec succès.',
            'boutique' => $boutique
        ], 201);
    }

    // GET : Affiche une boutique spécifique
    public function show($id)
    {
        // $boutique = Boutique::with('adresse', 'produits')->findOrFail($id);
        // return response()->json($boutique, 200);

        $boutique = Boutique::with('adresse', 'produits')->findOrFail($id);
        return response()->json(['boutique' => $boutique], 200);
    }

    // PUT/PATCH : Met à jour une boutique
    public function update(Request $request, $id)
    {
        $boutique = Boutique::findOrFail($id);

        $validatedData = $request->validate([
            'nom' => 'nullable|string|max:255',
            'adresse' => 'nullable|string',
            'telephone' => 'nullable|string',
            'adresse_id' => 'nullable|exists:adresses,id',
            'user_id' => 'nullable|exists:adresses,id',

        ]);

        $boutique->update($validatedData);

        // return response()->json($boutique, 200); // 200 OK

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

        // return response()->json(null, 204); // 204 No Content
        return response()->json(['message' => 'Région supprimée avec succès']);

    }
}
