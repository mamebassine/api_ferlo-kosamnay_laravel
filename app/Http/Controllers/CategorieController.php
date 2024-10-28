<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use Illuminate\Http\Request;

class CategorieController extends Controller
{
    /**
     * Affiche une liste de toutes les catégories.
     */
    public function index()
    {
        $categories = Categorie::all();
        return response()->json($categories);
    }

    /**
     * Stocke une nouvelle catégorie dans la base de données.
     */
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|string',
            'nom_complet' =>  ['required','string','max:14',  'regex:/^[a-zA-Z\s]*$/'],
            'description' => ['nullable', 'string','max:255', 'regex:/^[a-zA-Z\s]*$/'],
]);

        $categorie = Categorie::create($request->all());
        return response()->json([
            'message' => 'Catégorie créée avec succès',
            'categorie' => $categorie
        ], 201);
        


        // return response()->json($categorie, 201);
    }

    // Méthode pour afficher les détails d'une catégorie spécifique
public function show($id)
{
    // Rechercher la catégorie dans la base de données à partir de l'ID fourni
    $categorie = Categorie::find($id);

    // Vérifier si la catégorie existe
    if (!$categorie) {
        // Si la catégorie n'est pas trouvée, retourner une réponse JSON avec un message d'erreur et le statut HTTP 404 (Non trouvé)
        return response()->json(['message' => 'Catégorie non trouvée'], 404);
    }

    // Si la catégorie est trouvée, retourner ses détails sous forme de JSON avec un statut HTTP 200 (OK)
    return response()->json($categorie);
}

// Méthode pour mettre à jour les informations d'une catégorie spécifique
public function update(Request $request, $id)
{
    // Valider les données envoyées dans la requête pour s'assurer que les champs sont corrects
    $validatedData = $request->validate([
        'image' => 'required|string',
        'nom_complet' =>  ['required','string','max:14',  'regex:/^[a-zA-Z\s]*$/'],
        'description' => ['nullable', 'string','max:255', 'regex:/^[a-zA-Z\s]*$/'],
    ]);

    
    // Rechercher la catégorie à partir de l'ID fourni
    $categorie = Categorie::find($id);

    // Vérifier si la catégorie existe
    if (!$categorie) {
        // Si la catégorie n'est pas trouvée, retourner une réponse JSON avec un message d'erreur et le statut HTTP 404
        return response()->json(['message' => 'Catégorie non trouvée'], 404);
    }

    // Mettre à jour la catégorie avec les nouvelles données validées
    $categorie->update($validatedData);

    // Retourner les détails mis à jour de la catégorie sous forme de JSON avec un statut HTTP 200 (OK)
    return response()->json($categorie, status: 200);
    // return response()->json(['message' => 'Categorie mis à jour avec succès', 'Categorie' => $categorie], 200);

}

// Méthode pour supprimer une catégorie spécifique
public function destroy($id)
{
    try {
        // Rechercher la catégorie à partir de l'ID fourni
        $categorie = Categorie::find($id);

        // Vérifier si la catégorie existe
        if (!$categorie) {
            // Si la catégorie n'est pas trouvée, retourner une réponse JSON avec un message d'erreur et le statut HTTP 404
            return response()->json(['message' => 'Catégorie non trouvée'], 404);
        }

        // Si la catégorie est trouvée, la supprimer de la base de données
        $categorie->delete();

        // Retourner un message de succès après suppression avec un statut HTTP 200 (OK)
        return response()->json(['message' => 'Catégorie supprimée avec succès'], 200);
    } catch (\Exception $e) {
        // Si une erreur survient lors de la suppression, retourner un message d'erreur avec un statut HTTP 500 (Erreur serveur)
        return response()->json(['message' => 'Erreur lors de la suppression'], 500);
    }
}


}
