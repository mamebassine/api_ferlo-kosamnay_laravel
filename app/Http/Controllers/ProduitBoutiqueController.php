<?php

namespace App\Http\Controllers;

use App\Models\ProduitBoutique;
use Illuminate\Http\Request;

class ProduitBoutiqueController extends Controller
{
    // Liste toutes les associations produit-boutique
    public function index()
    {
        $produitBoutiques = ProduitBoutique::with(['produit', 'boutique'])->get(); // Chargement des relations produit et boutique
        return response()->json($produitBoutiques, 200);
    }

    // Crée une nouvelle association produit-boutique
    public function store(Request $request)
    {
        // Validation des données
        $request->validate([
            'produit_id' => 'required|exists:produits,id',
            'boutique_id' => 'required|exists:boutiques,id',
            'quantite' => 'required|integer|min:0',
        ]);

        // Création de l'association produit-boutique
        $produitBoutique = ProduitBoutique::create($request->only('produit_id', 'boutique_id', 'quantite'));

        return response()->json($produitBoutique, 201);
    }

    // Affiche une association produit-boutique spécifique
    public function show($id)
    {
        $produitBoutique = ProduitBoutique::with(['produit', 'boutique'])->find($id);

        if (!$produitBoutique) {
            return response()->json(['message' => 'Association produit-boutique non trouvée'], 404);
        }

        return response()->json($produitBoutique, 200);
    }

    // Met à jour une association produit-boutique existante
    public function update(Request $request, $id)
    {
        // Validation des données
        $request->validate([
            'produit_id' => 'required|exists:produits,id',
            'boutique_id' => 'required|exists:boutiques,id',
            'quantite' => 'required|integer|min:0',
        ]);

        $produitBoutique = ProduitBoutique::find($id);

        if (!$produitBoutique) {
            return response()->json(['message' => 'Association produit-boutique non trouvée'], 404);
        }

        // Mise à jour des données
        $produitBoutique->update($request->only('produit_id', 'boutique_id', 'quantite'));

        return response()->json($produitBoutique, 200);
    }

    // Supprime une association produit-boutique
    public function destroy($id)
    {
        $produitBoutique = ProduitBoutique::find($id);

        if (!$produitBoutique) {
            return response()->json(['message' => 'Association produit-boutique non trouvée'], 404);
        }

        $produitBoutique->delete();

        return response()->json(['message' => 'Association produit-boutique supprimée avec succès'], 200);
    }
}
