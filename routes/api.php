<?php

use App\Models\adresse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\BoutiqueController;
use App\Http\Controllers\CommandeController;
use App\Http\Controllers\PaiementController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\LigneCommandeController;
use App\Http\Controllers\ProduitBoutiqueController;




// les utilisateurs
    Route::apiResource('users', UserController::class);

    // Routes personnalisées pour récupérer les représentants et les clients
    Route::post('representant', [UserController::class, 'addRepresentant']);
    Route::get('clients', [UserController::class, 'getClients']);

    // // // Route pour ajouter un représentant (accessible uniquement aux administrateurs)
    // Route::middleware(['admin'])->post('representants', [UserController::class, 'addRepresentant']);

// Route pour l'inscription des clients
Route::post('register', [UserController::class, 'register']);  
Route::post('login', [UserController::class, 'login'])->name('login');

// // Route pour ajouter un représentant (accessible uniquement aux administrateurs)
// Route::middleware(['auth:api', 'admin'])->post('/representants', [UserController::class, 'addRepresentant']);

// Deconnexion pour tous les utilisateurs
Route::post('/logout', [UserController::class, 'logout'])->name('logout');


// Routes pour les catégories
Route::apiResource('categories', CategorieController::class);

// Routes pour les produits
// Route::apiResource('produits', ProduitController::class);

Route::get('produits', [ProduitController::class, 'index']);
Route::post('produits', [ProduitController::class, 'store']);
Route::post('produits/{produit}', [ProduitController::class, 'update']);
Route::delete('produits/{produit}', [ProduitController::class, 'destroy']);
Route::get('produits/{produit}', [ProduitController::class, 'show']);


// Routes pour les boutiques
Route::apiResource('boutiques', controller: BoutiqueController::class);

// Routes pour les adresse
Route::apiResource('regions', controller: RegionController::class);
Route::apiResource('produitBoutique', ProduitBoutiqueController::class);

// Route::apiResource('notifications', NotificationController::class);

    // Route::post('/notifications/{id}/mark-as-read', 'NotificationController@markAsRead');
    // Route::post('/notifications/mark-all-as-read', 'NotificationController@markAllAsRead');

Route::post('/commandes/{id}/confirmation', [EmailController::class, 'envoyerConfirmationCommande']);
//Route::post('/paiements/{id}/confirmation', [EmailController::class, 'envoyerConfirmationPaiement']);

// 

// Ou, pour une API
Route::apiResource('commandes', CommandeController::class);
Route::post('/commandes', [CommandeController::class, 'store']);



Route::middleware('jwt.auth')->group(function () {
   

 Route::apiResource('lignes_commandes', LigneCommandeController::class);

// Route::post('lignes_commandesk', [LigneCommandeController::class,'store']);












Route::post('/commandes/{id}/confirmation', [EmailController::class, 'envoyerConfirmationCommande']);
Route::post('/paiements/{id}/confirmation', [EmailController::class, 'envoyerConfirmationPaiement']);




// Route::middleware(['jwt.auth'])->group(function () {
//     Route::get('profile', [UserController::class, 'profile']);  
//     Route::post('refresh-token', [UserController::class, 'refreshToken']);
//     Route::post('logout', [UserController::class, 'logout']);
// });
// Route::post('logout', [UserController::class, 'logout'])->middleware('auth:api');
// Route pour obtenir le profil de l'utilisateur connecté
// Route::get('profile', [UserController::class, 'profile'])->middleware('auth:api');
// Route::post('refresh-token', [UserController::class, 'refreshToken'])->middleware('auth:api');
// Route::middleware('jwt.auth')->group(function () {
//     Route::apiResource('lignes_commandes', LigneCommandeController::class);

//     // Routes pour les catégories
//     Route::apiResource('categories', CategorieController::class);
//     // Routes pour les produits
//     Route::apiResource('produits', ProduitController::class);

//     // Routes pour les boutiques
//     Route::apiResource('boutiques', controller: BoutiqueController::class);
//     // Routes pour les boutiques
//     Route::apiResource('adresses', controller: adresseController::class);
//     Route::apiResource('produitBoutique', ProduitBoutiqueController::class);
//     Route::apiResource('notifications', NotificationController::class);
// Route::post('/commandes/{id}/confirmation', [EmailController::class, 'envoyerConfirmationCommande']);
// Route::post('/paiements/{id}/confirmation', [EmailController::class, 'envoyerConfirmationPaiement']);


// });
// Routes pour les paiements




//Route::post('/paiement/initier/{ligneCommandeId}', [PaiementController::class, 'initierPaiement']);


});



Route::middleware('auth:api')->group(function () {
    Route::get('/paiements', [PaiementController::class, 'index']);
    Route::post('/paiements', [PaiementController::class, 'store']);
    Route::get('/paiements/{id}', [PaiementController::class, 'show']);
    Route::put('/paiements/{id}', [PaiementController::class, 'update']);
    Route::delete('/paiements/{id}', [PaiementController::class, 'destroy']);

    Route::get('/notifications', [NotificationController::class, 'index']);
});

//Route::middleware('auth:api')->get('/notifications', [NotificationController::class, 'index']);

// Route::middleware('jwt.auth')->group(function () {
//     // Route pour créer un paiement
//     Route::post('/paiements', [PaiementController::class, 'createPayment']);

//     // Route pour obtenir tous les paiements
//     Route::get('/paiements', [PaiementController::class, 'index']);

//     // Route pour obtenir un paiement par ID
//     Route::get('/paiements/{id}', [PaiementController::class, 'show']);

//     // Route pour mettre à jour un paiement
//     Route::put('/paiements/{id}', [PaiementController::class, 'update']);

//     // Route pour supprimer un paiement
//     Route::delete('/paiements/{id}', [PaiementController::class, 'destroy']);
// });



