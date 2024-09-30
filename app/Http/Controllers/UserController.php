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
            'nom_complet' => 'required|string|max:255',  // Le nom complet est obligatoire, de type string, et ne peut pas dépasser 255 caractères
            'telephone' => 'required|string|max:15',  // Le numéro de téléphone est obligatoire et limité à 15 caractères
            'email' => 'required|string|email|max:255|unique:users',  // L'email est obligatoire, doit être unique et ne peut pas dépasser 255 caractères
            'password' => 'required|string|min:6|confirmed',  // Le mot de passe est obligatoire, doit faire au moins 6 caractères et être confirmé
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

    /*Connexion d'un utilisateur.*/
    public function login(Request $request)
    {
        // Récupération des informations d'identification de l'utilisateur (email et mot de passe)
        $credentials = $request->only('email', 'password');

        // Vérification des identifiants avec JWT. Si incorrects, renvoyer une erreur 401
        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Identifiants invalides'], 401);
        }

        // // Si les identifiants sont corrects, renvoyer le token JWT
        // return response()->json(compact('token'));

        return response()->json([
            'message' => 'Connexion réussie.',
            'token' => $token
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
    $this->authorize('admin'); // Vérifiez si l'utilisateur a le rôle d'administrateur

    // Validation des données
    $validator = Validator::make($request->all(), [
        'nom_complet' => 'required|string|max:255',
        'telephone' => 'required|string|max:15',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:6|confirmed',
    ]);

    if ($validator->fails()) {
        return response()->json($validator->errors(), 400);
    }

    // Création du représentant
    $representant = User::create([
        'nom_complet' => $request->nom_complet,
        'telephone' => $request->telephone,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => 'representant',
    ]);

    // return response()->json($representant, 201);

    return response()->json([
        'message' => 'Représentant ajouté avec succès.',
        'representant' => $representant
    ], 201);
}

}
