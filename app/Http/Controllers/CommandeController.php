<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommandeController extends Controller
{
    /**
     * Affiche la liste des commandes.
     */
    public function index()
    {
        // Récupérer toutes les commandes avec les utilisateurs associés
        $commandes = Commande::with('user')->get(); 
        return response()->json($commandes); // Retourner les commandes en format JSON
    }

    /**
     * Crée une nouvelle commande.
     */
    public function store(Request $request)
    {
        // Validation des données de la requête
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id', // Vérifie que l'ID de l'utilisateur est requis et existe
            'date' => 'required|date', // Vérifie que la date est requise et au format valide
            'statut' => 'required|in:en attente,l livré,en cours de traitement', // Vérifie que le statut est valide
            'total' => 'required|numeric', // Vérifie que le total est requis et doit être numérique
        ]);

        // Si la validation échoue, retourner les erreurs
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400); // 400 Bad Request
        }

        // Créer une nouvelle commande avec les données validées
        $commande = Commande::create($request->all());
        return response()->json($commande, 201); // Retourner la commande créée avec le statut 201 Created
    }

    /**
     * Affiche une commande spécifique.
     */
    public function show($id)
    {
        // Récupérer la commande par son ID, avec les informations de l'utilisateur associé
        $commande = Commande::with('user')->find($id);

        // Si la commande n'est pas trouvée, retourner une erreur
        if (!$commande) {
            return response()->json(['message' => 'Commande non trouvée'], 404); // 404 Not Found
        }

        return response()->json($commande); // Retourner la commande trouvée en format JSON
    }

    /**
     * Met à jour une commande spécifique.
     */
    public function update(Request $request, $id)
    {
        // Récupérer la commande par son ID
        $commande = Commande::find($id);

        // Si la commande n'est pas trouvée, retourner une erreur
        if (!$commande) {
            return response()->json(['message' => 'Commande non trouvée'], 404); // 404 Not Found
        }

        // Validation des données de la requête
        $validator = Validator::make($request->all(), [
            'user_id' => 'sometimes|required|exists:users,id', // Vérifie que l'ID de l'utilisateur existe, si fourni
            'date' => 'sometimes|required|date', // Vérifie que la date est au format valide, si fournie
            'statut' => 'sometimes|required|in:en attente,l livré,en cours de traitement', // Vérifie que le statut est valide, si fourni
            'total' => 'sometimes|required|numeric', // Vérifie que le total est numérique, si fourni
        ]);

        // Si la validation échoue, retourner les erreurs
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400); // 400 Bad Request
        }

        // Mettre à jour la commande avec les données validées
        $commande->update($request->all());
        return response()->json($commande); // Retourner la commande mise à jour en format JSON
    }

    /**
     * Supprime une commande spécifique.
     */
    public function destroy($id)
    {
        // Récupérer la commande par son ID
        $commande = Commande::find($id);

        // Si la commande n'est pas trouvée, retourner une erreur
        if (!$commande) {
            return response()->json(['message' => 'Commande non trouvée'], 404); // 404 Not Found
        }

        // Supprimer la commande
        $commande->delete();
        return response()->json(['message' => 'Commande supprimée avec succès']); // Confirmation de la suppression
    }
}
