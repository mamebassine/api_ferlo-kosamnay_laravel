<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
            $userId = Auth::user()->id;
            

            // Récupérer toutes les notifications non lues pour l'utilisateur connecté
            $notifications = Notification::where('user_id', $userId)      // Trier par date de création, les plus récentes en premier
                                          ->get();

            return response()->json([
                    'status' => true,
                    'data' => $notifications
                ], 200);

    }

    // public function markAsRead($id)
    // {
    //     $notification = Notification::findOrFail($id);
    //     $notification->update(['lue' => true]); // Marquer comme lue
        
    //     return response()->json(['message' => 'Notification marquée comme lue']);
    // }

    public function markAsRead($id)
    {
        $notification = Notification::find($id);
        if ($notification) {
            $notification->is_read = true;
            $notification->save();
            return response()->json(['status' => true, 'message' => 'Notification marquée comme lue.']);
        }
        return response()->json(['status' => false, 'message' => 'Notification non trouvée.'], 404);
    }
    

    


    // public function markAsRead($id)
    // {
    //     $user = Auth::id();

    //     $notification = Notification::where('id', $id)
    //         ->where('user_id', $user)
    //         ->first();

    //     if ($notification) {
    //         $notification->update(['lue' => true]);
    //         return response()->json(['success' => true]);
    //     }

    //     return response()->json(['error' => 'Notification non trouvée'], 404);
    // }

    
}
