<?php

use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\BoutiqueController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProduitBoutiqueController;
use App\Http\Controllers\LigneCommandeController;
use App\Http\Controllers\EmailController;



// Route pour l'inscription des clients
Route::post('register', [UserController::class, 'register']);  
Route::post('login', [UserController::class, 'login'])->name('login');
// Route pour ajouter un représentant (accessible uniquement aux administrateurs)
Route::middleware(['auth:api', 'admin'])->post('/representants', [UserController::class, 'addRepresentant']);
// Deconnexion pour tous les utilisateurs
Route::post('/logout', [UserController::class, 'logout'])->name('logout');


Route::middleware(['jwt.auth'])->group(function () {
    Route::get('profile', [UserController::class, 'profile']);  
    Route::post('refresh-token', [UserController::class, 'refreshToken']);
    Route::post('logout', [UserController::class, 'logout']);
});


// Route::post('logout', [UserController::class, 'logout'])->middleware('auth:api');
// Route pour obtenir le profil de l'utilisateur connecté
// Route::get('profile', [UserController::class, 'profile'])->middleware('auth:api');
// Route::post('refresh-token', [UserController::class, 'refreshToken'])->middleware('auth:api');


Route::middleware('jwt.auth')->group(function () {
    Route::apiResource('lignes_commandes', LigneCommandeController::class);

    // Routes pour les catégories
    Route::apiResource('categories', CategorieController::class);
    // Routes pour les produits
    Route::apiResource('produits', ProduitController::class);

    // Routes pour les boutiques
    Route::apiResource('boutiques', controller: BoutiqueController::class);
    // Routes pour les boutiques
    Route::apiResource('regions', controller: RegionController::class);
    Route::apiResource('produitBoutique', ProduitBoutiqueController::class);
    Route::apiResource('notifications', NotificationController::class);




Route::post('/commandes/{id}/confirmation', [EmailController::class, 'envoyerConfirmationCommande']);
Route::post('/paiements/{id}/confirmation', [EmailController::class, 'envoyerConfirmationPaiement']);


});











