<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Mail\CommandePayee;
use App\Mail\Commande_creer;
use Illuminate\Http\Request;
use App\Models\LigneCommande;
use App\Models\ProduitBoutique;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class LigneCommandeController extends Controller
{
    /**
     * Affiche une liste des lignes de commande de l'utilisateur.
     */
    public function index(Request $request)
    {
        // Vérifie si l'utilisateur est authentifié
        if (!$request->user()) {
            return response()->json(['error' => 'Utilisateur non authentifié'], 401);
        }

        // Récupère les lignes de commande associées à l'utilisateur
        $user = $request->user();
        $lignesCommandes = LigneCommande::where('user_id', $user->id)
            ->with('ProduitBoutique') // Charge les produits associés via la relation many-to-many
            ->get();

        return response()->json($lignesCommandes);
    }



    public function store(Request $request)
    {
        // Vérifie si l'utilisateur est authentifié
        if (!$request->user()) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }
    
        // Valide les données de la requête
        $request->validate([
            'produits' => 'required|array',
            'produits.*.produit_boutique_id' => 'required|integer|exists:produit_boutique,id',
            'produits.*.quantite_totale' => 'required|integer|min:1',
            'produits.*.prix_totale' => 'required|numeric'
        ]);
    
        $userId = Auth::id();
        $date = now();
        $statut = 'en attente';
    
        try {
            // Variable pour stocker la commande créée
            $ligneCommande = null;
    
            // Utilisation d'une transaction pour garantir l'intégrité des données
            DB::transaction(function () use ($request, $userId, $date, $statut, &$ligneCommande) {
                // Crée une nouvelle ligne de commande unique
                $ligneCommande = LigneCommande::create([
                    'user_id' => $userId,
                    'date' => $date,
                    'statut' => $statut,
                    'quantite_totale' => collect($request->input('produits'))->sum('quantite_totale'),
                    'prix_totale' => collect($request->input('produits'))->sum(function ($produit) {
                        return $produit['prix_totale'] * $produit['quantite_totale'];
                    }),
                ]);
    
                // Associe les produits à la ligne de commande via la table pivot
                foreach ($request->input('produits') as $produit) {
                    $ligneCommande->produitBoutiques()->attach($produit['produit_boutique_id'], [
                        'quantite' => $produit['quantite_totale'],
                        'montant' => $produit['prix_totale']
                    ]);
                }
    
                // Récupère l'utilisateur connecté
                $user = Auth::user();
    
                // Récupère les représentants de la même adresse que l'utilisateur
                $representants = User::where('role', 'representant')
                    ->where('adresse', $user->adresse)
                    ->get();
    
                if ($representants->isEmpty()) {
                    throw new \Exception('Aucun représentant trouvé avec cette adresse');
                }
    
                // Envoie un email à chaque représentant
                foreach ($representants as $representant) {
                    Mail::to($representant->email)->send(new commande_creer($ligneCommande));
                }
            });
    
            // Si tout s'est bien passé, retourne la commande créée
            return response()->json(['success' => 'Commande ajoutée avec succès', 'commande' => $ligneCommande], 200);
    
        } catch (\Exception $e) {
            Log::error('Erreur: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString(),
            ]);
    
            return response()->json(['error' => 'Erreur lors de l\'ajout de la commande', 'message' => $e->getMessage()], 500);
        }
    }
    
    
    /**
     * Met à jour une ligne de commande existante.
     */
    public function update(Request $request, $id)
    {
        // Valide les nouvelles valeurs
        $request->validate([
            'quantite_totale' => 'required|integer|min:1',
            'prix_totale' => 'required|numeric'
        ]);

        $ligneCommande = LigneCommande::find($id);

        if (!$ligneCommande) {
            return response()->json(['error' => 'Ligne de commande non trouvée.'], 404);
        }

        // Met à jour les informations de la ligne de commande
        $ligneCommande->quantite_totale = $request->input('quantite_totale');
        $ligneCommande->prix_totale = $request->input('prix_totale');
        $ligneCommande->statut = $request->input('statut', $ligneCommande->statut);
        $ligneCommande->save();

        if ($ligneCommande->statut === 'payée') {
            Mail::to($request->user()->email)->send(new CommandePayee($ligneCommande));
        }

        return response()->json(['success' => 'Ligne de commande mise à jour avec succès.'], 200);
    }

    /**
     * Supprime une ligne de commande spécifique.
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
     * Affiche une ligne de commande spécifique.
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
