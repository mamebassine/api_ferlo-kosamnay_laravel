<?php

namespace App\Http\Controllers; // Déclaration de l'espace de noms pour le contrôleur

use App\Http\Controllers\Controller; // Importation de la classe Controller
use App\Models\Produit; // Importation du modèle Produit
use Illuminate\Http\Request; // Importation de la classe Request pour gérer les requêtes HTTP

class ProduitController extends Controller // Déclaration de la classe ProduitController qui étend le contrôleur de base
{
    // GET : Liste tous les produits
    public function index()
    {
        // Récupère tous les produits avec leurs catégories et boutiques associées et renvoie une réponse JSON
        return response()->json(Produit::with('categorie', 'boutiques','produitBoutique')->get(), 200);
    }

    // POST : Crée un nouveau produit
    public function store(Request $request)
    {
        // Valide les données de la requête entrante
        $validatedData = $request->validate([
            'categorie_id' => 'required|exists:categories,id', // La catégorie doit être requise et exister dans la table categories
            'image' => 'required|string', // L'image doit être requise et de type chaîne
            'description' => 'required|string', // La description doit être requise et de type chaîne
            'prix' => 'required|numeric', // Le prix doit être requis et de type numérique
            'quantite' => 'required|integer', // La quantité doit être requise et de type entier
            'reference' => 'required|string|unique:produits,reference', // La référence doit être requise, de type chaîne et unique dans la table produits
            'nom' => 'required|string', // Le nom doit être requis et de type chaîne
            // 'nom_complet' => 'required|string', // Ajoutez une validation pour nom_complet

        ]);

        // Crée un nouveau produit avec les données validées
        $produit = Produit::create($validatedData);

        // Renvoie le produit créé avec un statut 201 (Créé)
        return response()->json($produit->load('categorie', 'boutiques'), 201);
    }

    // GET : Affiche un produit spécifique
    public function show($id)
    {
        // Récupère un produit spécifique avec ses catégories et boutiques associées ou lance une exception si non trouvé
        $produit = Produit::with('categorie', 'boutiques','produitBoutique')->findOrFail($id);
        // Renvoie le produit trouvé avec un statut 200 (OK)
        return response()->json($produit, 200);
    }

    // PUT/PATCH : Met à jour un produit
    public function update(Request $request, $id)
    {
        // Récupère le produit à mettre à jour ou lance une exception si non trouvé
        $produit = Produit::findOrFail($id);

        // Valide les données de la requête entrante pour la mise à jour
        $validatedData = $request->validate([
            'categorie_id' => 'nullable|exists:categories,id', // La catégorie peut être laissée vide, mais doit exister si fournie
            'image' => 'nullable|string', // L'image peut être laissée vide, mais doit être de type chaîne si fournie
            'description' => 'nullable|string', // La description peut être laissée vide, mais doit être de type chaîne si fournie
            'prix' => 'nullable|numeric', // Le prix peut être laissé vide, mais doit être numérique si fourni
            'quantite' => 'nullable|integer', // La quantité peut être laissée vide, mais doit être entière si fournie
            'reference' => 'nullable|string|unique:produits,reference,' . $produit->id, // La référence peut être laissée vide, mais doit être unique si fournie, sauf pour le produit actuel
            'nom' => 'nullable|string', // Le nom peut être laissé vide, mais doit être de type chaîne si fourni
            // 'nom_complet' => 'nullable|string',

        ]);

        // Met à jour le produit avec les données validées
        $produit->update($validatedData);

        // Renvoie le produit mis à jour avec un statut 200 (OK)
        return response()->json($produit->load('categorie', 'boutiques'), 200);
    }

// DELETE : Supprime un produit
public function destroy($id)
{
    try {
        // Récupère le produit à supprimer ou lance une exception si non trouvé
        $produit = Produit::find($id);

        // Vérifie si le produit existe
        if (!$produit) {
            // Si le produit n'est pas trouvé, retourner une réponse JSON avec un message d'erreur et le statut HTTP 404
            return response()->json(['message' => 'Produit non trouvé'], 404);
        }

        // Supprime le produit
        $produit->delete();

        // Retourne un message de succès après suppression avec un statut HTTP 200 (OK)
        return response()->json(['message' => 'Produit supprimé avec succès'], 200);
    } catch (\Exception $e) {
        // Si une erreur survient lors de la suppression, retourner un message d'erreur avec un statut HTTP 500 (Erreur serveur)
        return response()->json(['message' => 'Erreur lors de la suppression'], 500);
    }
}

}