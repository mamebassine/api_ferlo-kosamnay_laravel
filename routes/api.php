<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;


use App\Http\Controllers\CategorieController;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\BoutiqueController;

// // Route pour obtenir les informations de l'utilisateur connecté
// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('jwt.auth');


// Route pour l'inscription des clients
Route::post('register', [UserController::class, 'register']);  // Inscription pour les clients
// Routes pour l'administrateur et le représentant
// Route::post('login', [UserController::class, 'login']); // Connexion pour tous les utilisateurs

Route::post('login', [UserController::class, 'login'])->name('login');


// Deconnexion pour tous les utilisateurs

Route::post('/logout', [UserController::class, 'logout'])->name('logout');
// Route pour ajouter un représentant (accessible uniquement aux administrateurs)
// Route::middleware(['auth:api', 'admin'])->post('/representants', [UserController::class, 'addRepresentant']);


// Route::post('logout', [UserController::class, 'logout'])->middleware('auth:api');
// Route pour obtenir le profil de l'utilisateur connecté
// Route::get('profile', [UserController::class, 'profile'])->middleware('auth:api');
// Route::post('refresh-token', [UserController::class, 'refreshToken'])->middleware('auth:api');



// Routes pour les catégories
Route::apiResource('categories', CategorieController::class);
// Routes pour les produits
Route::apiResource('produits', ProduitController::class);
// Routes pour les boutiques
Route::apiResource('boutiques', BoutiqueController::class);



