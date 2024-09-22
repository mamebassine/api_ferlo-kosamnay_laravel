<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Produit;
use Illuminate\Http\Request;


class ProduitController extends Controller
{
    
    // Affiche une liste des produits
    public function index()
    {
    // Charger tous les produits avec la relation vers la catégorie
     $produits = Produit::with('categorie')->get();
        return response()->json($produits);
    }


    // Affiche les détails d'un produit spécifique avec sa catégorie
    public function show(Produit $produit)
    {
    // Charger le produit avec la relation vers la catégorie
        return response()->json($produit->load('categorie'));
    }


    //Créer un nouveau produit (store)
    public function store(Request $request)
    {
// Validation des données entrantes
        $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'required|string',
            'prix' => 'required|numeric',
            'quantite' => 'required|integer',
            'image' => 'nullable|image',
            'categorie_id' => 'required|exists:categories,id',
        ]);
// Créer une nouvelle instance du produit sans l'image pour le moment

        $produit = new Produit($request->except('image'));
        // Gérer le téléchargement de l'image si elle est fournie
       // $produit = new Produit($request->all());

               // Gestion de l'upload de l'image
             if ($request->hasFile('image')) {
        // Stocker l'image dans le répertoire 'images' dans le disque public
            $path = $request->file('image')->store('images', 'public');
            $produit->image = $path;
        }
        // Sauvegarder le produit dans la base de données
         $produit->save();
        // Retourner le produit nouvellement créé avec le statut 201 (Créé)
        return response()->json($produit, 201);
    }


// Met à jour un produit
    public function update(Request $request, $id)
{
     // Validation des données
        $request->validate([
        'nom' => 'required|string|max:255',
        'description' => 'required|string',
        'prix' => 'required|numeric',
        'quantite' => 'required|integer',
        'image' => 'nullable|image',
        'categorie_id' => 'required|exists:categories,id', // Valider que la catégorie existe
    ]);
        // Trouver le produit à partir de l'ID
       $produit = Produit::findOrFail($id);
       // $produit->update($request->all());
      // Mettre à jour les champs du produit sans l'image
     $produit->update($request->except('image'));
     // Gérer la mise à jour de l'image si une nouvelle est téléchargée
        if ($request->hasFile('image')) {
        // Stocker la nouvelle image et remplacer l'ancienne

        $path = $request->file('image')->store('images', 'public');
        $produit->image = $path;
        $produit->save();
    }
        // Retourner le produit mis à jour avec un statut 200 (OK)
        return response()->json(['message' => 'Produit mis à jour avec succès', 'produit' => $produit], 200);
    }


    // Supprime un produit
    public function destroy(Produit $produit)
    {
        // Supprimer le produit de la base de données
        $produit->delete();

        // Retourner une réponse JSON avec un statut 204 (Pas de contenu) pour indiquer que la suppression a été effectuée avec succès
        
        // return response()->json(null, 204);
        return response()->json(['message' => ' Produit supprimée avec succès'], 200);

        
    }
}
