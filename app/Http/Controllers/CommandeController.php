<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\ProduitBoutique;
use App\Models\LigneCommande;
use Illuminate\Http\Request;

class CommandeController extends Controller
{
    public function index()
    {
        $commandes = Commande::all();
        return response()->json($commandes);
    }

   
// Afficher un formulaire pour créer une nouvelle commande
    public function create()
    {
        $produits = ProduitBoutique::all();
        $lignesCommande = LigneCommande::all();
        return view('commandes.create', compact('produits', 'lignesCommande'));
    }

    // Enregistrer une nouvelle commande
    public function store(Request $request)
    {
        // $request->validate([
        //     'produit_boutique_id' => 'required',
        //     'ligne_commande_id' => 'required',
        //     'quantite' => 'required|integer',
        //     'montant' => 'required|numeric',
        // ]);

        Commande::create($request->all());

        return redirect()->route('commandes.index')->with('success', 'Commande créée avec succès.');
    }

    // Afficher les détails d'une commande
    public function show($id)
    {
        $commande = Commande::with(['produitBoutique', 'ligneCommande'])->findOrFail($id);
        return view('commandes.show', compact('commande'));
    }

    // Afficher un formulaire pour modifier une commande
    public function edit($id)
    {
        $commande = Commande::findOrFail($id);
        $produits = ProduitBoutique::all();
        $lignesCommande = LigneCommande::all();
        return view('commandes.edit', compact('commande', 'produits', 'lignesCommande'));
    }

    // Mettre à jour une commande
    public function update(Request $request, $id)
    {
        $request->validate([
            'produit_boutique_id' => 'required',
            'ligne_commande_id' => 'required',
            'quantite' => 'required|integer',
            'montant' => 'required|numeric',
        ]);

        $commande = Commande::findOrFail($id);
        $commande->update($request->all());

        return redirect()->route('commandes.index')->with('success', 'Commande mise à jour avec succès.');
    }

    // Supprimer une commande
    public function destroy($id)
    {
        $commande = Commande::findOrFail($id);
        $commande->delete();

        return redirect()->route('commandes.index')->with('success', 'Commande supprimée avec succès.');
    }
}
