<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Mail\CommandePayee;
use App\Mail\Commande_creer;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Models\LigneCommande;
use App\Models\ProduitBoutique;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Notifications\NotificationRepresentant;

class LigneCommandeController extends Controller
{


    /**
     * Affiche une liste des lignes de commande de l'utilisateur.
     */
    // public function index(Request $request)
    // {
    //     // Vérifie si l'utilisateur est authentifié
    //     if (!$request->user()) {
    //         return response()->json(['error' => 'Utilisateur non authentifié'], 401);
    //     }

    //     // Récupère les lignes de commande associées à l'utilisateur
    //     $user = $request->user();
    //     $lignesCommandes = LigneCommande::where('user_id', $user->id)
    //         ->with('ProduitBoutique') // Charge les produits associés via la relation many-to-many
    //         ->get();

    //     return response()->json($lignesCommandes);
    // }


    
    public function index(Request $request)
    {
        // Filtrage en fonction du statut si besoin
        $statut = $request->query('statut');
        
        // Récupère les lignes de commande avec les informations de l'utilisateur
        $lignesCommandes = LigneCommande::when($statut, function($query, $statut) {
                return $query->where('statut', $statut);
            })
            ->with('user:id,nom_complet') // Charge les informations nécessaires de l'utilisateur
            ->get();
    
        // Ajoute le nom complet de l'utilisateur à chaque ligne de commande
        $lignesCommandes->transform(function ($ligne) {
            $ligne->nom_complet = $ligne->user->nom_complet ?? 'Nom non disponible';
            unset($ligne->user); // Supprime l'objet `user` de la réponse JSON si nécessaire
            return $ligne;
        });
    
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
                    Notification::create([
                        'user_id' => $representant->id,
                        'objet' => 'Nouvelle commande reçue', // Ajout de l'objet
                        'description' => "Nouvelle commande de {$user->name} d'un montant de {$ligneCommande->prix_totale}CFA", // Ajout de la description
                        'lue' => 0
                    ]);
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
     * Mettre à jour le statut d'une commande.
     */public function updateStatut($id, Request $request)
{
    Log::info("Tentative de mise à jour du statut pour la commande ID : {$id}");

    // Validation du champ statut
    $request->validate([
        'statut' => 'required|string' // assure que le statut est présent et de type chaîne
    ]);

    $ligneCommande = LigneCommande::find($id);

    if ($ligneCommande) {
        $ligneCommande->statut = $request->input('statut');
        $ligneCommande->save();

        Log::info("Statut mis à jour avec succès pour la commande ID : {$id}");
        return response()->json(['message' => 'Statut mis à jour avec succès'], 200);
    } else {
        Log::error("Ligne de commande non trouvée pour l'ID : {$id}");
        return response()->json(['message' => 'Ligne de commande non trouvée'], 404);
    }
}

    
    

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





//  public function statistiques(Request $request)
// {
//     // On récupère les commandes et on les regroupe par statut
//     $statistiques = LigneCommande::select('statut', DB::raw('count(*) as total'))
//         ->groupBy('statut')
//         ->get();

//     // Renvoie les statistiques sous forme de réponse JSON
//     return response()->json($statistiques);
// }





 /*Statistiques pour les commandes en attente*/
    public function statistiquesEnAttente()
    {
        $statistiques = LigneCommande::where('statut', 'en attente')
            ->count();

        return response()->json([
            'statut' => 'en attente',
            'total' => $statistiques
        ]);
    }

    /*Statistiques pour les commandes livrées.*/
    public function statistiquesLivree()
    {
        $statistiques = LigneCommande::where('statut', 'livré')
            ->count();

        return response()->json([
            'statut' => 'livré',
            'total' => $statistiques
        ]);
    }

    /*Statistiques pour les commandes en cours de traitement.*/
    public function statistiquesEnCoursDeTraitement()
    {
        $statistiques = LigneCommande::where('statut', 'en cours de traitement')
            ->count();

        return response()->json([
            'statut' => 'en cours de traitement',
            'total' => $statistiques
        ]);
    }







/*Supprime une ligne de commande spécifique.
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




