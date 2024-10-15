<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProduitBoutique;

class PanierController extends Controller
{
    // Ajouter un produit au panier
    public function ajouterAuPanier(Request $request)
    {
        $request->validate([
            'produit_boutique_id' => 'required|integer',
            'quantite' => 'required|integer|min:1',
        ]);

        $produitId = $request->input('produit_boutique_id');
        $quantite = $request->input('quantite');

        // Récupérer le panier de la session ou initialiser un panier vide
        $panier = session()->get('panier', []);

        // Si le produit est déjà dans le panier, on augmente la quantité
        if (isset($panier[$produitId])) {
            $panier[$produitId]['quantite'] += $quantite;
        } else {
            // Sinon, on ajoute le produit avec ses informations
            $produit = ProduitBoutique::find($produitId);
            $panier[$produitId] = [
                'nom' => $produit->nom,
                'quantite' => $quantite,
                'prix' => $produit->prix,
            ];
        }

        // Sauvegarder le panier dans la session
        session()->put('panier', $panier);

        return response()->json(['message' => 'Produit ajouté au panier']);
    }

    // Voir le contenu du panier
    public function voirPanier()
    {
        $panier = session()->get('panier', []);
        return response()->json($panier);
    }

    // Mettre à jour la quantité d'un produit
    public function mettreAJourPanier(Request $request)
    {
        $request->validate([
            'produit_boutique_id' => 'required|integer',
            'quantite' => 'required|integer|min:1',
        ]);

        $panier = session()->get('panier', []);
        $produitId = $request->input('produit_boutique_id');
        $quantite = $request->input('quantite');

        if (isset($panier[$produitId])) {
            $panier[$produitId]['quantite'] = $quantite;
            session()->put('panier', $panier);
            return response()->json(['message' => 'Quantité mise à jour']);
        }

        return response()->json(['message' => 'Produit non trouvé dans le panier'], 404);
    }

    // Supprimer un produit du panier
    public function supprimerDuPanier($produitId)
    {
        $panier = session()->get('panier', []);

        if (isset($panier[$produitId])) {
            unset($panier[$produitId]);
            session()->put('panier', $panier);
            return response()->json(['message' => 'Produit supprimé du panier']);
        }

        return response()->json(['message' => 'Produit non trouvé dans le panier'], 404);
    }

    // Passer à la caisse
    public function checkout()
    {
        // Ici, tu peux traiter la commande, créer une ligne de commande, etc.
        // Récupérer le panier
        $panier = session()->get('panier');

        if (!$panier) {
            return response()->json(['message' => 'Le panier est vide'], 400);
        }

        // Logique pour créer la commande finale et vider le panier après le paiement
        // ...

        // Vider le panier après le checkout
        session()->forget('panier');

        return response()->json(['message' => 'Commande finalisée']);
    }
}
