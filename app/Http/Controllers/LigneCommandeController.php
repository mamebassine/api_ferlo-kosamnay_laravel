<?php

namespace App\Http\Controllers;

use App\Models\LigneCommande; // Assurez-vous que le modèle LigneCommande est créé
use App\Models\ProduitBoutique; // Assurez-vous que le modèle ProduitBoutique est créé
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LigneCommandeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Vérifie si l'utilisateur est authentifié
        if (!$request->user()) {
            return response()->json(['error' => 'Utilisateur non authentifié'], 401);
        }

        $user = $request->user();

        // Récupérer toutes les lignes de commande associées à cet utilisateur
        // $lignesCommandes = LigneCommande::where('user_id', $user->id)->with(relations: 'produit')->get();

        $lignesCommandes = LigneCommande::where('user_id', $user->id)->with(relations: 'ProduitBoutique')->get();

        // Retourner les lignes de commande sous forme de réponse JSON
        return response()->json($lignesCommandes);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Vérifie si l'utilisateur est authentifié
        if (!$request->user()) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        $request->validate([
            'produit_boutique_id' => 'required|exists:produit_boutique,id',
            'quantite_totale' => 'required|integer|min:1',
            'prix_totale' => 'required|numeric'
        ]);

        // Crée une nouvelle ligne de commande en utilisant les données fournies
        $ligneCommande = LigneCommande::create([
            'produit_boutique_id' => $request->input('produit_boutique_id'),
            'user_id' => Auth::id(),
            'date' => now(),
            'statut' => 'en attente', // Statut par défaut
            'quantite_totale' => $request->input('quantite_totale'),
            'prix_totale' => $request->input('prix_totale')
        ]);

        return response()->json($ligneCommande, 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'quantite_totale' => 'required|integer|min:1',
            'prix_totale' => 'required|numeric'
        ]);

        $ligneCommande = LigneCommande::find($id);

        if (!$ligneCommande) {
            return response()->json(['error' => 'Ligne de commande non trouvée.'], 404);
        }

        $ligneCommande->quantite_totale = $request->input('quantite_totale');
        $ligneCommande->prix_totale = $request->input('prix_totale');
        $ligneCommande->statut = $request->input('statut', $ligneCommande->statut); // Optionnel

        $ligneCommande->save();

        return response()->json(['success' => 'Ligne de commande mise à jour avec succès.'], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $ligneCommande = LigneCommande::find($id);

        if (!$ligneCommande) {
            return response()->json(['error' => 'Ligne de commande non trouvée.'], 404);
        }

        $ligneCommande->delete();

        return response()->json(['success' => 'Ligne de commande supprimée avec succès.'], 200);
    }

    /**
     * Affiche une ligne de commande spécifique
     */
    public function show($id)
    {
        $ligneCommande = LigneCommande::find($id);

        if (!$ligneCommande) {
            return response()->json(['error' => 'Ligne de commande non trouvée.'], 404);
        }

        return response()->json($ligneCommande, 200);
    }
}