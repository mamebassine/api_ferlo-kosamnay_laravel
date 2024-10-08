<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Mail\CommandePayee; // Import du Mailable CommandePayee
use Illuminate\Support\Facades\Mail; // Import de la façade Mail
use App\Mail\CommandeConfirmee; // Import du Mailable CommandeConfirmee
use App\Models\LigneCommande; // Assurez-vous que le modèle LigneCommande est créé
use App\Models\ProduitBoutique; // Assurez-vous que le modèle ProduitBoutique est créé

class LigneCommandeController extends Controller
{
    /* Affiche une liste des lignes de commande de l'utilisateur.
    */
    public function index(Request $request)
    {
        // Vérifie si l'utilisateur est authentifié
        if (!$request->user()) {
            return response()->json(['error' => 'Utilisateur non authentifié'], 401); //Retourne une erreur si non authentifié
        }
        Log::info($request->user());

        $user = $request->user(); // Récupère l'utilisateur connecté

        // Récupérer toutes les lignes de commande associées à cet utilisateur
        // $lignesCommandes = LigneCommande::where('user_id', $user->id)->with(relations: 'produit')->get();

        $lignesCommandes = LigneCommande::where('user_id', $user->id)
        ->with(relations: 'ProduitBoutique') // Charge la relation avec les produits
        ->get();

        // Retourner les lignes de commande sous forme de réponse JSON
        return response()->json($lignesCommandes);
    }

    /*
    Crée une nouvelle ligne de commande.
    */
    public function store(Request $request)
    {
        // Vérifie si l'utilisateur est authentifié
        if (!$request->user()) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }
    
        // Valide les données de la requête avant de créer la ligne de commande
        $request->validate([
            'produit_boutique_id' => 'required|integer', 
            'quantite_totale' => 'required|integer|min:1', 
            'prix_totale' => 'required|numeric'
        ]);
    
        // Crée une nouvelle ligne de commande
        $ligneCommande = LigneCommande::create([
            'produit_boutique_id' => $request->input('produit_boutique_id'),
            'user_id' => Auth::id(),
            'date' => now(),
            'statut' => 'en attente',
            'quantite_totale' => $request->input('quantite_totale'),
            'prix_totale' => $request->input('prix_totale')
        ]);
    
        // Récupérer l'utilisateur connecté
        $user = Auth::user();
    
        try {
            // Récupérer les utilisateurs avec le rôle 'representant' et la même adresse email que l'utilisateur connecté
            $representants = User::where('role', 'representant')
                                ->where('adresse', $user->adresse) // Filtrer par email identique
                                ->get();
    
            // Vérifier s'il y a des représentants avant d'envoyer des emails
            if ($representants->isEmpty()) {
                return response()->json(['error' => 'Aucun représentant trouvé avec cette adresse '], 404);
            }
    
            // Envoyer un email à chaque représentant
            foreach ($representants as $representant) {
                Mail::to($representant->email)->send(new CommandeConfirmee($ligneCommande));
            }
    
            // Retourner une réponse de succès si tous les emails sont envoyés
            return response()->json(['success' => 'Emails envoyés avec succès'], 200);
    
        } catch (\Exception $e) {
            // Enregistrer les détails de l'erreur dans les logs
            Log::error('Erreur d\'envoi d\'email: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString(),
            ]);
    
            // Afficher le message d'erreur exact (à utiliser uniquement en développement)
            return response()->json([
                'error' => 'Erreur lors de l\'envoi de l\'email',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    
    /*Met à jour une ligne de commande existante.
     */
    public function update(Request $request, $id)
    {
                // Valide les nouvelles valeurs de la ligne de commande

        $request->validate([
         'quantite_totale' => 'required|integer|min:1', // La quantité doit être un entier valide
            'prix_totale' => 'required|numeric' // Le prix doit être un nombre valide
        ]);
        // Trouve la ligne de commande à mettre à jour

        $ligneCommande = LigneCommande::find($id);

        if (!$ligneCommande) {
            return response()->json(['error' => 'Ligne de commande non trouvée.'], 404);
        }
        // Met à jour les informations de la ligne de commande

        $ligneCommande->quantite_totale = $request->input('quantite_totale');
        $ligneCommande->prix_totale = $request->input('prix_totale');
        $ligneCommande->statut = $request->input('statut', $ligneCommande->statut); // Optionnel
        // Sauvegarde les modifications
          $ligneCommande->save();


        // Si la commande est payée, envoi d'un email de confirmation de paiement
        if ($ligneCommande->statut === 'payée') {
            Mail::to($request->user()->email)->send(new CommandePayee($ligneCommande));
        }
                // Retourne un message de succès après mise à jour

            return response()->json(['success' => 'Ligne de commande mise à jour avec succès.'], 200);
    }

    /*Supprime une ligne de commande spécifique.
     */
    public function destroy($id)
    {
                // Trouve la ligne de commande à supprimer

        $ligneCommande = LigneCommande::find($id);
        // Si la ligne de commande n'existe pas, retourne une erreur

        if (!$ligneCommande) {
            return response()->json(['error' => 'Ligne de commande non trouvée.'], 404);
        }
        // Supprime la ligne de commande

        $ligneCommande->delete();
        // Retourne un message de succès après suppression

        return response()->json(['success' => 'Ligne de commande supprimée avec succès.'], 200);
    }

    /**
     * Affiche une ligne de commande spécifique
     */
    public function show($id)
    {
                // Trouve la ligne de commande par son ID

        $ligneCommande = LigneCommande::find($id);
        // Si la ligne de commande n'existe pas, retourne une erreur

        if (!$ligneCommande) {
            return response()->json(['error' => 'Ligne de commande non trouvée.'], 404);
        }
        // Retourne la ligne de commande trouvée sous forme de réponse JSON

        return response()->json($ligneCommande, 200);
    }
}