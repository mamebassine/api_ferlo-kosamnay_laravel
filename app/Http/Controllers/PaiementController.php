<?php

namespace App\Http\Controllers;

use App\Models\Paiement; // Assurez-vous d'importer votre modèle
use Illuminate\Http\Request;

class PaiementController extends Controller
{
    /**
     * Afficher une liste de tous les paiements.
     */
    public function index()
    {
        $paiements = Paiement::all(); // Récupère tous les paiements
        return response()->json($paiements);
    }

    /**
     * Créer un nouveau paiement.
     */
    public function create(Request $request)
    {
        // Validation des données entrantes
        $validatedData = $request->validate([
            'ligne_commande_id' => 'required|exists:ligne_commandes,id', // Vérifie si l'ID de la ligne de commande existe
            'montant' => 'required|numeric',
            'date' => 'required|date',
            'type' => 'required|in:espece, wallet' // Vérifie que le type est soit 'espece' soit 'wallet'
        ]);

        // Créer le paiement
        $paiement = Paiement::create($validatedData);
        return response()->json($paiement, 201); // Retourne le paiement créé
    }

    /**
     * Afficher un paiement spécifique par ID.
     */
    public function show($id)
    {
        $paiement = Paiement::findOrFail($id); // Trouve le paiement ou échoue
        return response()->json($paiement);
    }

    /**
     * Mettre à jour un paiement existant.
     */
    public function update(Request $request, $id)
    {
        $paiement = Paiement::findOrFail($id); // Trouve le paiement ou échoue

        // Validation des données entrantes
        $validatedData = $request->validate([
            'ligne_commande_id' => 'nullable|exists:ligne_commandes,id', // Vérifie si l'ID de la ligne de commande existe, mais autorise les valeurs null
            'montant' => 'nullable|numeric', // Autorise les valeurs null pour le montant
            'date' => 'nullable|date', // Autorise les valeurs null pour la date
            'type' => 'nullable|in:espece, wallet' // Autorise les valeurs null pour le type
        ]);

        // Mettre à jour le paiement
        $paiement->update(array_filter($validatedData)); // Filtrer les valeurs nulles avant la mise à jour
        return response()->json($paiement);
    }

    /**
     * Supprimer un paiement.
     */
    public function destroy($id)
    {
        $paiement = Paiement::findOrFail($id); // Trouve le paiement ou échoue
        $paiement->delete(); // Supprime le paiement
        return response()->json(null, 204); // Retourne un statut 204 No Content
    }
}
