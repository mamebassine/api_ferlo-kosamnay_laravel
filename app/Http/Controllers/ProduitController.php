<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Produit;
use Illuminate\Http\Request;

class ProduitController extends Controller
{
    // GET : Liste tous les produits
    public function index()
    {
        return response()->json(Produit::with('categorie', 'boutiques')->get(), 200);
    }

    // POST : Crée un nouveau produit
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'categorie_id' => 'required|exists:categories,id',
            'image' => 'required|string',
            'description' => 'required|string',
            'prix' => 'required|numeric',
            'quantite' => 'required|integer',
            'reference' => 'required|string|unique:produits,reference',
        ]);

        $produit = Produit::create($validatedData);

        return response()->json($produit, 201); // 201 Created
    }

    // GET : Affiche un produit spécifique
    public function show($id)
    {
        $produit = Produit::with('categorie', 'boutiques')->findOrFail($id);
        return response()->json($produit, 200);
    }

    // PUT/PATCH : Met à jour un produit
    public function update(Request $request, $id)
    {
        $produit = Produit::findOrFail($id);

        $validatedData = $request->validate([
            'categorie_id' => 'sometimes|exists:categories,id',
            'image' => 'sometimes|string',
            'description' => 'sometimes|string',
            'prix' => 'sometimes|numeric',
            'quantite' => 'sometimes|integer',
            'reference' => 'sometimes|string|unique:produits,reference,' . $produit->id,
        ]);

        $produit->update($validatedData);

        return response()->json($produit, 200); // 200 OK
    }

    // DELETE : Supprime un produit
    public function destroy($id)
    {
        $produit = Produit::findOrFail($id);
        $produit->delete();

        return response()->json(null, 204); // 204 No Content
    }
}
