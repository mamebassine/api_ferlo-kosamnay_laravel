<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProduitController extends Controller
{
    // GET : Liste tous les produits
    public function index()
    {
        return response()->json(Produit::with('categorie', 'boutiques', 'produitBoutique')->get(), 200);
    }

    // POST : Crée un nouveau produit avec upload d'image
    public function store(Request $request)
    {
        $validatedData = $request->validate([

            'categorie_id' => 'required|exists:categories,id',
             'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048', 

          'nom' =>  ['required','string','max:14',  'regex:/^[a-zA-Z\s]*$/'],
          
        // 'description' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s]*$/'],
        'description' => ['required', 'string', 'max:255', 'regex:/^[a-zA-ZÀ-ÿ\s]*$/'],

        'reference' =>['required','string','regex:/^[0-9]{3}[A-Za-z]{3}$/','unique:produits,reference'], 

            'prix' => 'required|numeric',
            'quantite' => 'required|integer',
        ]);

        // Traitement de l'image
        if ($request->hasFile('image')) {
            // Sauvegarde l'image dans le dossier 'uploads' et récupère le chemin
            $imagePath = $request->file('image')->store('uploads', 'public');
            $validatedData['image'] = $imagePath; // Ajoute le chemin de l'image au tableau de données validées
        }

        // Crée le produit avec les données validées
        $produit = Produit::create($validatedData);

        return response()->json($produit->load('categorie', 'boutiques'), 201);
    }

    // GET : Affiche un produit spécifique
    public function show($id)
    {
        // Récupérer le produit avec ses relations
        $produit = Produit::with('categorie', 'boutiques','produitBoutique')->find($id);

        // Vérifiez si le produit existe
        if (!$produit) {
            return response()->json(['message' => 'Produit non trouvé'], 404);
        }

        return response()->json($produit, 200);
    }

    public function update(Request $request, $id)
    {
        $produit = Produit::findOrFail($id); // Cette ligne vérifie déjà si le produit existe
        
        // Validation des données
        $validatedData = $request->validate([
         'nom' =>  ['required','string','max:14',  'regex:/^[a-zA-Z\s]*$/'],
         'reference' => ['required', 'string', 'regex:/^[0-9]{3}[A-Za-z]{3}$/', 'unique:produits,reference,' . $id],
         'description' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Zàâäéèêëîïôûù\s]*$/'],
            
        //     'categorie_id' => 'nullable|exists:categories,id',
        // //     'image' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        //     'prix' => 'nullable|numeric',
        //     'quantite' => 'nullable|integer',
        ]);
        
        // Gestion de l'upload de l'image
        if ($request->hasFile('image')) {
            if ($produit->image) {
                Storage::disk('public')->delete($produit->image);
            }
            
            $imagePath = $request->file('image')->store('uploads', 'public');
            $validatedData['image'] = $imagePath;
        }
        
        $produit->update($validatedData);
    
        return response()->json($produit->load('categorie', 'boutiques'), 200);
    }

    // DELETE : Supprime un produit et son image
    public function destroy($id)
    {
        try {
            $produit = Produit::findOrFail($id);

            // Supprimer l'image associée si elle existe
            if ($produit->image) {
                Storage::disk('public')->delete($produit->image);
            }

            // Supprime le produit
            $produit->delete();

            return response()->json(['message' => 'Produit supprimé avec succès'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erreur lors de la suppression'], 500);
        }
    }




// Méthode pour obtenir le nombre total de produits
        public function nombreProduits()
        {
            $nombreProduits = Produit::count();
            return response()->json(['nombre_produits' => $nombreProduits], 200);
        }

//      public function nombreProduitsMois()
// {
//     // Regrouper les produits par mois et compter le nombre de produits pour chaque mois
//     $nombreProduitsParMois = Produit::select(
//             DB::raw('MONTH(created_at) as mois'), // Récupère le mois
//             DB::raw('YEAR(created_at) as annee'), // Récupère l'année
//             DB::raw('count(*) as nombre_produits') // Compte le nombre de produits pour chaque mois
//         )
//         ->groupBy(DB::raw('YEAR(created_at)'), DB::raw('MONTH(created_at)')) // Grouper par année et mois
//         ->orderBy(DB::raw('YEAR(created_at)'), 'asc') // Trier par année (croissant)
//         ->orderBy(DB::raw('MONTH(created_at)'), 'asc') // Trier par mois (croissant)
//         ->get();

//     // Retourner les données sous forme de tableau avec des noms de mois et les quantités
//     $resultat = $nombreProduitsParMois->map(function ($item) {
//         // Convertir le numéro du mois en nom de mois
//         $mois = \Carbon\Carbon::createFromFormat('m', $item->mois)->format('F'); // Par exemple, 'January'
//         return [
//             'mois' => $mois,
//             'produits' => $item->nombre_produits,
//         ];
//     });

//     return response()->json(['nombre_produits' => $resultat], 200);
// }





public function nombreProduitsTemps()
{
    // Récupérer et grouper les produits par année, mois, semaine, jour et heure
    $nombreProduitsParTemps = Produit::select(
            DB::raw('YEAR(created_at) as annee'),          // Année
            DB::raw('MONTH(created_at) as mois'),           // Mois
            DB::raw('WEEK(created_at) as semaine'),         // Semaine
            DB::raw('DAY(created_at) as jour'),             // Jour
            DB::raw('HOUR(created_at) as heure'),           // Heure
            DB::raw('count(*) as nombre_produits')          // Compte le nombre de produits
        )
        ->groupBy(
            DB::raw('YEAR(created_at)'),
            DB::raw('MONTH(created_at)'),
            DB::raw('WEEK(created_at)'),
            DB::raw('DAY(created_at)'),
            DB::raw('HOUR(created_at)')
        )
        ->orderBy(DB::raw('YEAR(created_at)'), 'asc')
        ->orderBy(DB::raw('MONTH(created_at)'), 'asc')
        ->orderBy(DB::raw('WEEK(created_at)'), 'asc')
        ->orderBy(DB::raw('DAY(created_at)'), 'asc')
        ->orderBy(DB::raw('HOUR(created_at)'), 'asc')
        ->get();

    // Formater le résultat en ajoutant les noms de mois et structurer les données pour chaque intervalle de temps
    $resultat = $nombreProduitsParTemps->map(function ($item) {
        $mois = \Carbon\Carbon::createFromFormat('m', $item->mois)->format('F'); // Convertir le mois en nom de mois
        return [
            'annee' => $item->annee,
            'mois' => $mois,
            'semaine' => $item->semaine,
            'jour' => $item->jour,
            'heure' => $item->heure,
            'produits' => $item->nombre_produits,
        ];
    });

    return response()->json(['nombre_produits' => $resultat], 200);
}
}







































// namespace App\Http\Controllers; // Déclaration de l'espace de noms pour le contrôleur

// use App\Http\Controllers\Controller; // Importation de la classe Controller
// use App\Models\Produit; // Importation du modèle Produit
// use Illuminate\Http\Request; // Importation de la classe Request pour gérer les requêtes HTTP

// class ProduitController extends Controller // Déclaration de la classe ProduitController qui étend le contrôleur de base
// {
//     // GET : Liste tous les produits
//     public function index()
//     {
//         // Récupère tous les produits avec leurs catégories et boutiques associées et renvoie une réponse JSON
//         return response()->json(Produit::with('categorie', 'boutiques','produitBoutique')->get(), 200);
//     }

//     // POST : Crée un nouveau produit
//     public function store(Request $request)
//     {
//         // Valide les données de la requête entrante
//         $validatedData = $request->validate([
//             'categorie_id' => 'required|exists:categories,id', // La catégorie doit être requise et exister dans la table categories
//             'image' => 'required|string', // L'image doit être requise et de type chaîne
//             'description' => 'required|string', // La description doit être requise et de type chaîne
//             'prix' => 'required|numeric', // Le prix doit être requis et de type numérique
//             'quantite' => 'required|integer', // La quantité doit être requise et de type entier
//             'reference' => 'required|string|unique:produits,reference', // La référence doit être requise, de type chaîne et unique dans la table produits
//             'nom' => 'required|string', // Le nom doit être requis et de type chaîne
//             // 'nom_complet' => 'required|string', // Ajoutez une validation pour nom_complet

//         ]);

//         // Crée un nouveau produit avec les données validées
//         $produit = Produit::create($validatedData);

//         // Renvoie le produit créé avec un statut 201 (Créé)
//         return response()->json($produit->load('categorie', 'boutiques'), 201);
//     }

//     // GET : Affiche un produit spécifique
//     public function show($id)
//     {
//         // Récupère un produit spécifique avec ses catégories et boutiques associées ou lance une exception si non trouvé
//         $produit = Produit::with('categorie', 'boutiques','produitBoutique')->findOrFail($id);
//         // Renvoie le produit trouvé avec un statut 200 (OK)
//         return response()->json($produit, 200);
//     }

//     // PUT/PATCH : Met à jour un produit
//     public function update(Request $request, $id)
//     {
//         // Récupère le produit à mettre à jour ou lance une exception si non trouvé
//         $produit = Produit::findOrFail($id);

//         // Valide les données de la requête entrante pour la mise à jour
//         $validatedData = $request->validate([
//             'categorie_id' => 'nullable|exists:categories,id', // La catégorie peut être laissée vide, mais doit exister si fournie
//             'image' => 'nullable|string', // L'image peut être laissée vide, mais doit être de type chaîne si fournie
//             'description' => 'nullable|string', // La description peut être laissée vide, mais doit être de type chaîne si fournie
//             'prix' => 'nullable|numeric', // Le prix peut être laissé vide, mais doit être numérique si fourni
//             'quantite' => 'nullable|integer', // La quantité peut être laissée vide, mais doit être entière si fournie
//             'reference' => 'nullable|string|unique:produits,reference,' . $produit->id, // La référence peut être laissée vide, mais doit être unique si fournie, sauf pour le produit actuel
//             'nom' => 'nullable|string', // Le nom peut être laissé vide, mais doit être de type chaîne si fourni
//             // 'nom_complet' => 'nullable|string',

//         ]);

//         // Met à jour le produit avec les données validées
//         $produit->update($validatedData);

//         // Renvoie le produit mis à jour avec un statut 200 (OK)
//         return response()->json($produit->load('categorie', 'boutiques'), 200);
//     }

// // DELETE : Supprime un produit
// public function destroy($id)
// {
//     try {
//         // Récupère le produit à supprimer ou lance une exception si non trouvé
//         $produit = Produit::find($id);

//         // Vérifie si le produit existe
//         if (!$produit) {
//             // Si le produit n'est pas trouvé, retourner une réponse JSON avec un message d'erreur et le statut HTTP 404
//             return response()->json(['message' => 'Produit non trouvé'], 404);
//         }

//         // Supprime le produit
//         $produit->delete();

//         // Retourne un message de succès après suppression avec un statut HTTP 200 (OK)
//         return response()->json(['message' => 'Produit supprimé avec succès'], 200);
//     } catch (\Exception $e) {
//         // Si une erreur survient lors de la suppression, retourner un message d'erreur avec un statut HTTP 500 (Erreur serveur)
//         return response()->json(['message' => 'Erreur lors de la suppression'], 500);
//     }
// }