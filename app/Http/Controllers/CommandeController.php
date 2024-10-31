<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Commande;
use Illuminate\Http\Request;
use App\Models\LigneCommande;
use App\Models\ProduitBoutique;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CommandeController extends Controller
{
    public function index()
    {

        $commandes = Commande::with('produitBoutique.produit','ligneCommande')->get();
        return response()->json($commandes);
    }
    public function mesCommandes(Request $request)
    {
        $user = Auth::user();
    
        // Vérifier que l'utilisateur est authentifié
        if (!$user) {
            Log::info("Utilisateur non authentifié");
            return response()->json(['error' => 'Utilisateur non authentifié'], 401);
        }
    
        // Log des informations sur l'utilisateur authentifié
        Log::info("Utilisateur authentifié : ", ['user_id' => $user->id, 'role' => $user->role]);
    
        // Récupérer les commandes associées à cet utilisateur
        $commandes = Commande::with(['produitBoutique.produit', 'ligneCommande'])
            ->whereHas('ligneCommande', function($query) use ($user) {
                $query->where('user_id', $user->id); // Assurez-vous que cette colonne existe
            })
            ->get();
    
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