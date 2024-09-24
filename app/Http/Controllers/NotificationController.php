<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{
    /*Afficher la liste de toutes les notifications.
     
     */
    public function index()
    {
        $notifications = Notification::all();
        Log::info('Liste des notifications récupérée.');
        return response()->json($notifications);
    }

    /*Stocker une nouvelle notification dans la base de données.
     
     */
    public function store(Request $request)
    {
        // Valider les données de la requête
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id', // L'utilisateur doit exister
            'objet' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        // Créer la notification avec les données validées
        $notification = Notification::create($validated);
        Log::info('Nouvelle notification créée :', $validated);
        return response()->json($notification, 201);
    }

    /* Afficher la notification spécifiée.
   
     */
    public function show(Notification $notification)
    {
        Log::info('Notification récupérée :', ['id' => $notification->id]);
        return response()->json($notification);
    }

    /* Mettre à jour la notification spécifiée.
     
     */
    public function update(Request $request, Notification $notification)
    {
        // Valider les données de la requête
        $validated = $request->validate([
            'objet' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'lue' => 'sometimes|boolean',
        ]);

        // Mettre à jour la notification avec les données validées
        $notification->update($validated);
        Log::info('Notification mise à jour :', ['id' => $notification->id]);
        return response()->json($notification);
    }

    /*Supprimer la notification spécifiée.
     
     */
    public function destroy(Notification $notification)
    {
        $notification->delete();
        Log::info('Notification supprimée :', ['id' => $notification->id]);
        return response()->json(null, 204);
    }
}
