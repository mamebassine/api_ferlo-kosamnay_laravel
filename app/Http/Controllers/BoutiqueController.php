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
        return response()->json(Boutique::with('region', 'produits')->get(), 200);
    }

    // POST : Crée une nouvelle boutique
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nom' => 'required|string|max:255',
            'adresse' => 'required|string',
            'telephone' => 'required|string',
            'region_id' => 'required|exists:regions,id',
        ]);

        $boutique = Boutique::create($validatedData);

        return response()->json($boutique, 201); // 201 Created
    }

    // GET : Affiche une boutique spécifique
    public function show($id)
    {
        $boutique = Boutique::with('region', 'produits')->findOrFail($id);
        return response()->json($boutique, 200);
    }

    // PUT/PATCH : Met à jour une boutique
    public function update(Request $request, $id)
    {
        $boutique = Boutique::findOrFail($id);

        $validatedData = $request->validate([
            'nom' => 'sometimes|string|max:255',
            'adresse' => 'sometimes|string',
            'telephone' => 'sometimes|string',
            'region_id' => 'sometimes|exists:regions,id',
        ]);

        $boutique->update($validatedData);

        return response()->json($boutique, 200); // 200 OK
    }

    // DELETE : Supprime une boutique
    public function destroy($id)
    {
        $boutique = Boutique::findOrFail($id);
        $boutique->delete();

        return response()->json(null, 204); // 204 No Content
    }
}
