<?php

namespace App\Http\Controllers;

use App\Models\Paiement;
use App\Models\LigneCommande;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class PaiementController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        
        // Vérifiez que l'utilisateur est authentifié
        if (!$user) {
            return response()->json(['error' => 'Utilisateur non authentifié'], 401);
        }
    
        // Récupérer toutes les commandes de l'utilisateur
        $commandes = $user->commandes;
        // Vérifiez si l'utilisateur a des commandes
        if ($commandes->isEmpty()) {
            return response()->json(['message' => 'Aucune commande trouvée'], 404);
        }
    
        // Récupérer les paiements associés aux commandes de l'utilisateur
        $paiements = Paiement::whereIn('ligne_commande_id', $commandes->pluck('id'))->get();
        // Vérifier s'il y a des paiements pour les commandes
        $paiementsNonRegles = $paiements->filter(function ($paiement) use ($commandes) {
            return !$commandes->contains('id', $paiement->ligne_commande_id);
        });
    
        // Si des paiements ne sont pas réglés, renvoyer ces paiements
        if ($paiementsNonRegles->isNotEmpty()) {
            return response()->json($paiementsNonRegles); // Renvoie la liste des paiements non réglés
        }
    
        // Si tous les paiements sont réglés, renvoyer un message
        return response()->json(['message' => $paiements], 200);
    }
    
    
//CREEACTION
public function store(Request $request)
{
    // Validation des données d'entrée
    $request->validate([
        'ligne_commande_id' => 'required|exists:ligne_commandes,id',
        'montant' => 'required|numeric',
        'type' => 'required|in:espece,wallet', // Limité aux méthodes acceptées
    ]);

    try {
        // Créer une nouvelle instance de Paiement
        // $paiement = Paiement::create([
        //     'ligne_commande_id' => $request->ligne_commande_id,
        //     'montant' => $request->montant,
        //     'date' => now(),
        //     'type' => $request->type,
        // ]);
           // Vérifier si la commande existe
           $commande = LigneCommande::find($request['ligne_commande_id']);
           if (!$commande) {
               return response()->json(['message' => 'Événement non trouvé.'], 404);
           }
        
        // Préparer les données de la transaction pour Naboopay
        $transactionData = [
            'method_of_payment' => ['WAVE'], // Assurez-vous d'ajouter la méthode de paiement
            'products' => [
                [
                    'name' => "Paiement pour la commande #" . $commande->id,
                    'category' => 'Paiement achat',
                    'amount' => $request->montant,
                    'quantity' => 1,
                    'description' => 'Paiement de la commande ID : ' . $commande->id,
                ]
            ],
            'success_url' => 'https://smartdevafrica.com',
            'error_url' => 'https://smartdevafrica.com',
            'is_escrow' => false,
            'is_merchant' => false,
        ];
        //dd($transactionData); // Affichage des données pour débogage

      
        // Effectuer le paiement via Naboopay
        $response = $this->effectuerPaiement($transactionData);
        // Log des données de transaction
        if(!$response || $response->getStatusCode() !== 200){
            return response()->json(['message' => 'La transaction a échoué.', $response], 500);
        }
         // Récupérer les détails de la transaction
         $transactionDetails = json_decode($response->getBody(), true);
         $payment_url = $transactionDetails['checkout_url'];

         return response()->json(['payment_url' => $payment_url], 201);
        // if ($naboopayResponse && isset($naboopayResponse['checkout_url'])) {
        //     return response()->json(['payment_url' => $naboopayResponse['checkout_url']], 201);
        // }

        // return response()->json(['message' => 'Erreur lors de la création de la transaction Naboopay.'], 500);
    } catch (\Exception $e) {
        Log::error('Erreur lors de la création du paiement : ' . $e->getMessage(), [
            'request' => $request->all(),
            'trace' => $e->getTraceAsString(),
        ]);
        return response()->json(['message' => 'Erreur lors de la création du paiement : ' . $e->getMessage()], 500);
    }
}

private function effectuerPaiement(array $transactionData)
{
    $client = new Client(['verify' => false]);
    try {
        $response = $client->request('PUT', 'https://api.naboopay.com/api/v1/transaction/create-transaction', [
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => 'Bearer naboo-a88111eb-a5ac-42c6-a159-20a47a5c2cc6.dcde2a62-7f06-40b2-bef2-747638e734ec',
            ],
            'json' => $transactionData,
            
        ]);

        // $body = json_decode($response->getBody()->getContents(), true);

        return $response; // Retourne le corps de la réponse de Naboopay

    } catch (RequestException $e) {
        Log::error('Erreur lors de la création de la transaction:', ['error' => $e->getMessage()]);
        return response()->json(['message' => 'trans' . $e->getMessage()], 500);
    }
}

public function webhook(Request $request)
{
    // Gestion des notifications de Naboopay pour le traitement des paiements
    $status = $request->input('transaction_status');
    $orderId = $request->input('order_id');
    $ligne_commande_id = $request->input('ligne_commande_id');
    $montant = $request->input('montant');
    $type = $request->input('type');

  
    // Récupérer le paiement associé
    $commande = LigneCommande::where('id', $ligne_commande_id)->first();

    if (!$commande->isEmpty()) {
        Log::warning('Aucun paiement trouvé pour la commande: ' . $orderId);
        return response()->json(['message' => 'Aucun paiement trouvé pour cette commande.'], 404);
    }

    if ($status === 'success') {
        // Mettre à jour le statut du paiement
        $commande->update(['status' => 'livré']);
        return response()->json(['message' => 'Paiement validé.'], 200);
    }
     // Créer la transaction
     $paiement = Paiement::create([
        'order_id' => $orderId,
        'montant' => $montant,
        'type' => $type,
        'ligne_commande_id' => $commande->id,
        'date' => now(),
    ]);
    return response()->json(['message' => 'Paiement validé et tickets créés.'], 200);
    // Log::warning('Le paiement a échoué pour la commande: ' . $orderId);
    // return response()->json(['message' => 'Le paiement a échoué.'], 400);
}


public function destroy($id)
{
    // Récupérer le paiement à supprimer
    $paiement = Paiement::find($id);

    // Vérifier si le paiement existe
    if (!$paiement) {
        return response()->json(['message' => 'Paiement non trouvé.'], 404);
    }

    try {
        // Supprimer le paiement
        $paiement->delete();

        // Retourner un message de confirmation
        return response()->json(['message' => 'Paiement supprimé avec succès.'], 200);
    } catch (\Exception $e) {
        // En cas d'erreur lors de la suppression
        Log::error('Erreur lors de la suppression du paiement : ' . $e->getMessage());
        return response()->json(['message' => 'Erreur lors de la suppression du paiement.'], 500);
    }
}

}