<?php

namespace App\Http\Controllers;

use App\Models\User;  // Importation du modèle User
use Illuminate\Http\Request;  // Importation de la classe Request pour manipuler les requêtes HTTP
use Illuminate\Support\Facades\Hash;  // Importation pour le hachage des mots de passe
use Illuminate\Support\Facades\Validator;  // Importation pour la validation des données
use Tymon\JWTAuth\Facades\JWTAuth;  // Importation pour gérer l'authentification JWT
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class UserController extends Controller
{
        use AuthorizesRequests;  // Ajout du trait pour permettre l'autorisation

    /*Enregistrement d'un nouvel utilisateur.*/
    public function register(Request $request)
    {
        // Validation des données de la requête
        $validator = Validator::make($request->all(), [
            'nom_complet' => 'required|string|max:255',  
            'telephone' => 'required|string|max:15',  
            'email' => 'required|string|email|max:255|unique:users',
            'adresse' => 'required|string|max:255',  // Le nom complet est obligatoire, de type string, et ne peut pas dépasser 255 caractères

            'password' => 'required|string|min:6|confirmed',
        ]);

        // Si la validation échoue, renvoyer les erreurs en réponse JSON avec un code 400
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // Création de l'utilisateur avec les données validées
        $user = User::create([
            'nom_complet' => $request->nom_complet,  // Nom de l'utilisateur
            'telephone' => $request->telephone,  // Téléphone de l'utilisateur
            'email' => $request->email,  // Email de l'utilisateur
            'adresse' => $request->adresse,  // Nom de l'utilisateur

            'password' => Hash::make($request->password),  // Hashage du mot de passe avant stockage pour plus de sécurité
            'role' => 'client',  // Rôle par défaut de l'utilisateur, ici "client"
        ]);

        // Génération du token JWT pour l'utilisateur nouvellement créé
        $token = JWTAuth::fromUser($user);

        // Renvoyer l'utilisateur et le token en réponse avec un code 201 (créé)
        // return response()->json(compact('user', 'token'), 201);
        return response()->json([
            'message' => 'Utilisateur enregistré avec succès.',
            'user' => $user,
            'token' => $token
        ], 201);
    }

    
/* Connexion d'un utilisateur. */
public function login(Request $request)
{
    // Récupération des informations d'identification de l'utilisateur (email et mot de passe)
    $credentials = $request->only('email', 'password');

    // Vérification des identifiants avec JWT. Si incorrects, renvoyer une erreur 401
    if (!$token = JWTAuth::attempt($credentials)) {
        return response()->json(['error' => 'Identifiants invalides'], 401);
    }

    // Récupération de l'utilisateur authentifié
    $user = JWTAuth::user();

    // Si les identifiants sont corrects, renvoyer le token JWT et les informations de l'utilisateur
    return response()->json([
        'message' => 'Connexion réussie.',
        'token' => $token,
        'user' => $user, // Retourne les détails de l'utilisateur connecté
        'role' => $user->role,
    ]);
}


    /*Obtenir les informations du profil de l'utilisateur connecté.*/
    public function profile()
    {
        // Récupérer l'utilisateur actuellement authentifié via le token JWT
        $user = JWTAuth::user();

        // Renvoyer les informations de l'utilisateur en réponse JSON
        return response()->json($user);
    }

    /*Méthode de déconnexion.*/
    public function logout()
    {
        // Invalidation du token JWT pour déconnecter l'utilisateur
        JWTAuth::invalidate(JWTAuth::getToken());

        // Renvoyer un message de confirmation de la déconnexion
        return response()->json(['message' => 'Déconnexion réussie.']);
    }

    /**
     * Rafraîchir le token JWT (lorsque celui-ci est expiré).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refreshToken()
    {
        // Rafraîchir le token JWT
        $token = JWTAuth::refresh(JWTAuth::getToken());

        // Renvoyer le nouveau token en réponse JSON
        return response()->json(compact('token'));
    }




    /* Ajout d'un représentant par l'administrateur. */

    public function addRepresentant(Request $request)
    {
        $validatedData = $request->validate([
            'nom_complet' => 'required|string|max:255',
            'adresse' => 'required|string|max:255',
            'telephone' => 'required|string|max:20',
            'email' => 'required|email|unique:users,email|max:255',
            'password' => 'required|string|min:8', // Assurez-vous que 'password_confirmation' est présent
        ]);
    
        // Création du représentant
        $representant = User::create([
            'nom_complet' => $validatedData['nom_complet'],
            'adresse' => $validatedData['adresse'],
            'telephone' => $validatedData['telephone'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'role' => 'representant',
        ]);
    
        return response()->json([
            'message' => 'Représentant ajouté avec succès.',
            'representant' => $representant
        ], 201);
    }
    






// Méthode pour récupérer tous les utilisateurs
public function index()
{
    $users = User::all();
    return response()->json($users, 200); 
}

// Méthode pour afficher les détails d'un utilisateur spécifique
public function show(User $user)
{
    return response()->json($user, 200);
}

/* Méthode pour supprimer un utilisateur */
public function destroy(User $user)
{
    $user->delete();
    return response()->json(null, 204);
}

/* Méthode pour récupérer les représentants */
public function getRepresentants()
{
    $representants = User::where('role', 'representant')->with('boutique')->get();
    return response()->json($representants, 200);
}

/* Méthode pour récupérer les clients */
public function getClients()
{
    $clients = User::where('role', 'client')->get();
    return response()->json($clients, 200);
}
    
}
