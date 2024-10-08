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
        <li><strong>Produit :</strong> {{ $ligneCommande->produitBoutique->nom }}</li>
        <li><strong>Quantité :</strong> {{ $ligneCommande->quantite_totale }}</li>
        <li><strong>Prix Total :</strong> {{ $ligneCommande->prix_totale }} FCA</li>
        <li><strong>Date de Commande :</strong> {{ $ligneCommande->created_at->format('d/m/Y H:i') }}</li>

    </ul>
    <p>Veuillez traiter cette commande dans les plus brefs délais.</p>
</body>
</html>
