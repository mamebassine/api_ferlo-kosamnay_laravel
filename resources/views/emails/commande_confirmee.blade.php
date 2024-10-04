<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation de Commande</title>
</head>
<body>
    <h1>Votre commande a été confirmée</h1>
    <p>Merci pour votre achat. Voici les détails de votre commande :</p>
    <ul>
        <li><strong>Produit :</strong> {{ $ligneCommande->produitBoutique->nom }}</li>
        <li><strong>Quantité :</strong> {{ $ligneCommande->quantite_totale }}</li>
        <li><strong>Prix Total :</strong> {{ $ligneCommande->prix_totale }} €</li>
    </ul>
    <p>Nous vous remercions pour votre confiance.</p>
</body>
</html>
