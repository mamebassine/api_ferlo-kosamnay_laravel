<?php

namespace App\Http\Controllers;

use App\Mail\commande_creer;
use App\Models\LigneCommande;
use App\Mail\CommandePayee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    // Envoi de l'e-mail de confirmation de commande
    public function envoyerCommandeCreer(Request $request, $id)
    {

                // Récupération de la ligne de commande
                $ligneCommande = LigneCommande::with('produitBoutiques')->findOrFail($id); // Charger la relation


        // // Récupération de la ligne de commande
        // $ligneCommande = LigneCommande::findOrFail($id);

        // Envoi de l'e-mail de confirmation de commande
        Mail::to($request->user()->email)->send(new commande_creer($ligneCommande));

        // Retourne une réponse JSON
        return response()->json(['message' => 'E-mail de confirmation envoyé avec succès'], 200);
    }

    // Envoi de l'e-mail de confirmation de paiement
    public function envoyerConfirmationPaiement(Request $request, $id)
    {
        // Récupération de la ligne de commande
        $ligneCommande = LigneCommande::findOrFail($id);

        // Envoi de l'e-mail de confirmation de paiement
        Mail::to($request->user()->email)->send(new CommandePayee($ligneCommande));

        // Retourne une réponse JSON
        return response()->json(['message' => 'E-mail de paiement envoyé avec succès'], 200);
    }
}
