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

    Route::get('representants', [UserController::class, 'getRepresentants']);

    Route::post('representants', [UserController::class, 'addRepresentant']);
    
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
Route::post('categories/{categories}', [CategorieController::class, 'update']);
Route::get('categories/{categories}', [CategorieController::class, 'show']);
Route::get('categories/{categories}', [CategorieController::class, 'index']);
Route::post('categories/{categories}', [CategorieController::class, 'store']);
Route::delete('categories/{categories}', [CategorieController::class, 'destroy']);


// Routes pour les produits
// Route::apiResource('produits', ProduitController::class);

Route::apiResource('produits', ProduitController::class);

// Route::get('produits', [ProduitController::class, 'index']);
// Route::post('/produits', [ProduitController::class, 'store']);
// Route::put('/produits/{id}', [ProduitController::class, 'update']);
// Route::delete('produits/{id}', [ProduitController::class, 'destroy']);
// Route::get('produits/{id}', [ProduitController::class, 'show']);





// Route::middleware('auth:api')->group(function (): void {

// Routes pour les produits
//Route::apiResource('produits', ProduitController::class);

// Route::get('produits', [ProduitController::class, 'index']);

// Route::post('produits', [ProduitController::class, 'store']);

// Route::put('produits/{id}', [ProduitController::class, 'update']);
// Route::delete('produits/{id}', [ProduitController::class, 'destroy']);
// Route::get('produits/{id}', [ProduitController::class, 'show']);



// Routes pour les boutiques
Route::get('boutiques', [BoutiqueController::class, 'index']); // Liste toutes les boutiques
Route::post('boutiques', [BoutiqueController::class, 'store']); // Crée une nouvelle boutique

Route::get('boutiques/{id}', [BoutiqueController::class, 'show']); // Affiche une boutique spécifique
Route::put('boutiques/{id}', [BoutiqueController::class, 'update']); // Met à jour une boutique
Route::delete('boutiques/{id}', [BoutiqueController::class, 'destroy']); // Supprime une boutique

//Route::apiResource('boutiques', controller: BoutiqueController::class);


// Routes pour les adresse
// Route::apiResource('regions', RegionController::class);
Route::get('regions', [RegionController::class, 'index']); 
Route::post('regions', [RegionController::class, 'store']); 

Route::get('regions/{id}', [RegionController::class, 'show']); 
Route::put('regions/{id}', [RegionController::class, 'update']); 
Route::delete('regions/{id}', [RegionController::class, 'destroy']); 








Route::apiResource('produitBoutique', ProduitBoutiqueController::class);


// Route::apiResource('notifications', NotificationController::class);
  // Route::post('/notifications/{id}/mark-as-read', 'NotificationController@markAsRead');
    // Route::post('/notifications/mark-all-as-read', 'NotificationController@markAllAsRead');
Route::post('/commandes/{id}/confirmation', [EmailController::class, 'envoyerConfirmationCommande']);
//Route::post('/paiements/{id}/confirmation', [EmailController::class, 'envoyerConfirmationPaiement']);

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



Route::middleware('auth:api')->group(function (): void {
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



