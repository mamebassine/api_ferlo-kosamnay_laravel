<?php

namespace App\Http\Controllers;
use Illuminate\Http\JsonResponse; 

use App\Models\LigneCommande; // Assurez-vous que le modèle LigneCommande est créé
use App\Models\ProduitBoutique; // Assurez-vous que le modèle ProduitBoutique est créé
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LigneCommandeController extends Controller
{
    /**
     * Affiche une liste des lignes de commande.
     */
    public function index(Request $request)
    {
        // Vérifie si l'utilisateur est authentifié
        if (!$request->user()) {
            return response()->json(['error' => 'Utilisateur non authentifié'], 401);
        }

        $user = $request->user();

        // Récupérer toutes les lignes de commande associées à cet utilisateur
        $lignesCommandes = LigneCommande::where('user_id', $user->id)->with('produitBoutique')->get();

        // Retourner les lignes de commande sous forme de réponse JSON
        return response()->json($lignesCommandes);
    }

    /**
     * Enregistre une nouvelle ligne de commande.
     */
    public function store(Request $request)
    {
        // Vérifie si l'utilisateur est authentifié
        if (!$request->user()) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        // Valide les données envoyées dans la requête
        $request->validate(['produit_boutique_id' => 'required|exists:produit_boutique,id',
            'user_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'statut' => 'required|in:en attente,livré,en cours de traitement,annulé',
            'quantite_totale' => 'required|integer',
            'prix_totale' => 'required|numeric',
        ]);

        // Crée une nouvelle ligne de commande en utilisant les données fournies
        $ligneCommande = LigneCommande::create([
            'produit_boutique_id' => $request->input('produit_boutique_id'), // ID du produit
            'user_id' => Auth::id(), // Utilisateur connecté
            'date' => now(), // Date actuelle
            'statut' => 'en attente', // Statut par défaut
            'quantite_totale' => $request->input('quantite_totale'), // Quantité totale commandée
            'prix_totale' => $request->input('prix_totale') // Prix total
        ]);

        // Retourner la ligne de commande nouvellement créée avec un statut 201 (créé)
        return response()->json($ligneCommande, 201);
    }

    /**
     * Met à jour une ligne de commande spécifique.
     */
    public function update(Request $request, $id)
    {
        // Valide les nouvelles données
        $request->validate([
            'produit_boutique_id' => 'required|exists:produit_boutique,id',
            'user_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'statut' => 'required|in:en attente,livré,en cours de traitement,annulé',
            'quantite_totale' => 'required|integer',
            'prix_totale' => 'required|numeric',
        ]);

        

        // Récupère la ligne de commande avec l'ID fourni
        $ligneCommande = LigneCommande::find($id);

        // Si la ligne de commande n'est pas trouvée, retourner une erreur 404
        if (!$ligneCommande) {
            return response()->json(['error' => 'Ligne de commande non trouvée.'], 404);
        }

        // Met à jour les données de la ligne de commande
        $ligneCommande->quantite_totale = $request->input('quantite_totale');
        $ligneCommande->prix_totale = $request->input('prix_totale');
        $ligneCommande->statut = $request->input('statut', $ligneCommande->statut); // Statut optionnel, par défaut l'ancien statut est conservé

        // Sauvegarde des modifications
        $ligneCommande->save();

        // Retourne une réponse de succès
        return response()->json(['success' => 'Ligne de commande mise à jour avec succès.'], 200);
    }

    /**
     * Supprime une ligne de commande spécifique.
     */
    public function destroy($id)
    {
        // Récupère la ligne de commande avec l'ID fourni
        $ligneCommande = LigneCommande::find($id);

        // Si la ligne de commande n'est pas trouvée, retourner une erreur 404
        if (!$ligneCommande) {
            return response()->json(['error' => 'Ligne de commande non trouvée.'], 404);
        }

        // Supprime la ligne de commande
        $ligneCommande->delete();

        // Retourne une réponse de succès
        return response()->json(['success' => 'Ligne de commande supprimée avec succès.'], 200);
    }

    /**
     * Affiche une ligne de commande spécifique.
     */
    // public function show($id)
    // {
    //     // Récupère la ligne de commande avec l'ID fourni
    //     $ligneCommande = LigneCommande::find($id);

    //     // Si la ligne de commande n'est pas trouvée, retourner une erreur 404
    //     if (!$ligneCommande) {
    //         return response()->json(['error' => 'Ligne de commande non trouvée.'], 404);
    //     }

    //     // Retourne la ligne de commande trouvée
    //     return response()->json($ligneCommande, 200);
    // }


    public function show(LigneCommande $ligneCommande): JsonResponse
    {
        // Inclure les relations produitBoutique et user
        return response()->json($ligneCommande->load('produitBoutique', 'user'));
    }
    
    
}
