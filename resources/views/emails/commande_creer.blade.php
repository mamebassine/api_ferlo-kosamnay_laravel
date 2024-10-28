<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouvelle Commande Reçue</title>
</head>
<body>
    <h1>Nouvelle Commande Reçue</h1>
    <p>Merci pour votre achat. Voici les détails de votre commande :</p>
   
    <ul>
        @foreach($ligneCommande->produitBoutiques as $produitBoutique)
            <li><strong>Produit :</strong> {{ $produitBoutique->produit->nom ?? 'N/A' }}</li> <!-- Vérifie que le produit existe -->
            <li><strong>Quantité :</strong> {{ $produitBoutique->pivot->quantite }}</li>
            <li><strong>Prix Unitaire :</strong> {{ $produitBoutique->pivot->montant }} FCA</li>
        @endforeach
        <li><strong>Prix Total :</strong> {{ $ligneCommande->prix_totale }} FCA</li>
        <li><strong>Date de Commande :</strong> {{ $ligneCommande->created_at->format('d/m/Y H:i') }}</li>
    </ul>
<p>Veuillez traiter cette commande dans les plus brefs délais.</p>
</body>
</html>
