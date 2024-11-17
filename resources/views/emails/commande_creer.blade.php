<!DOCTYPE html> 
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouvelle Commande Reçue</title>
    <style>
        /* Style général */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #FFF;
            color: #000;
            margin: 0;
            padding: 0;
        }

        /* Conteneur principal */
        .container {
            max-width: 700px;
            margin: 50px auto;
            padding: 25px;
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        /* Titre */
        h1 {
            font-size: 2.5rem;
            color: #3F7E44; /* Vert foncé */
            text-align: center;
            margin-bottom: 20px;
            letter-spacing: 1px;
        }

        /* Paragraphe */
        p {
            font-size: 1.1rem;
            margin-bottom: 20px;
            line-height: 1.6;
        }

        /* Liste des détails */
        ul {
            list-style-type: none;
            padding: 0;
        }

        ul li {
            margin: 15px 0;
            padding: 12px;
            background-color: #e8f5e9; /* Vert clair */
            border-left: 5px solid #3F7E44; /* Bordure verte */
            border-radius: 8px;
            font-size: 1.1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        ul li strong {
            color: #3F7E44; /* Texte en vert foncé */
            font-weight: 600;
        }

        /* Lien */
        a {
            color: #3F7E44;
            text-decoration: none;
            font-weight: bold;
            text-transform: uppercase;
        }

        a:hover {
            text-decoration: underline;
        }

        /* Pied de page */
        .footer {
            text-align: center;
            font-size: 0.9rem;
            color: #777;
            margin-top: 30px;
        }

        /* Responsiveness */
        @media (max-width: 768px) {
            .container {
                padding: 15px;
            }

            h1 {
                font-size: 2rem;
            }

            ul li {
                font-size: 1rem;
            }
        }

    </style>
</head>
<body>
    <div class="container">
        <h1>Nouvelle Commande Reçue</h1>
        <p>Une nouvelle commande a été reçue. Voici les détails de la commande à traiter :</p>
        
        <ul>
            @foreach($ligneCommande->produitBoutiques as $produitBoutique)
                <li><strong>Produit :</strong> <span>{{ $produitBoutique->produit->nom ?? 'N/A' }}</span></li>
                <li><strong>Quantité :</strong> <span>{{ $produitBoutique->pivot->quantite }}</span></li>
                <li><strong>Prix Unitaire :</strong> <span>{{ $produitBoutique->pivot->montant }} FCA</span></li>
            @endforeach
            <li><strong>Prix Total :</strong> <span>{{ $ligneCommande->prix_totale }} FCA</span></li>
            <li><strong>Date de Commande :</strong> <span>{{ $ligneCommande->created_at->format('d/m/Y H:i') }}</span></li>
        </ul>
        
        <p>Veuillez traiter cette commande dans les plus brefs délais.</p>
    </div>
    <div class="footer">
        &copy; 2024 Votre Entreprise. Tous droits réservés.
    </div>
</body>
</html>
