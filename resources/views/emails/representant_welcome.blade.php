<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenue</title>
    <style>
        /* Corps de la page */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f3f4f6;
            color: #000;
        }

        /* Conteneur principal */
        .container {
            max-width: 700px;
            margin: 50px auto;
            padding: 20px 30px;
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        /* Titre principal */
        h1 {
            text-align: center;
            font-size: 2.5rem;
            color: #3F7E44; /* Vert foncé */
            margin-bottom: 20px;
            letter-spacing: 1px;
        }

        /* Paragraphe */
        p {
            font-size: 1.1rem;
            line-height: 1.8;
            margin: 15px 0;
            color: #000;
        }

        /* Liste des identifiants */
        ul {
            padding-left: 0;
            margin: 20px 0;
            list-style: none;
        }

        ul li {
            background-color: #fff; /* Vert clair */
            border: 1px solid #99B951; /* Bordure verte */
            border-radius: 8px;
            padding: 12px 15px;
            margin-bottom: 10px;
            color: #000;
            font-size: 1rem;
        }

        ul li strong {
            color: #3F7E44; /* Texte en vert foncé pour les labels */
        }

        /* Lien */
        a {
            display: inline-block;
            color: #fff;
            background-color: #99B951;
            text-decoration: none;
            font-weight: bold;
            padding: 10px 20px;
            border-radius: 8px;
            text-align: center;
            margin-top: 20px;
        }

        a:hover {
            background-color: #3F7E44; /* Vert encore plus foncé */
            transition: background-color 0.3s ease;
        }

        /* Pied de page */
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 0.9rem;
            color: #000;
        }

    </style>
</head>
<body>
    <div class="container">
        <h1>Bienvenue, {{ $representant->nom_complet }}</h1>
        <p>Votre compte de représentant a été créé avec succès sur notre plateforme.</p>
        <p>Voici vos identifiants de connexion :</p>
        <ul>
            <li><strong>Email :</strong> {{ $email }}</li>
            <li><strong>Mot de passe :</strong> {{ $password }}</li>
        </ul>
        <p>Veuillez vous connecter et changer votre mot de passe après votre première connexion.</p>
        <p>Merci de faire partie de notre communauté !</p>
        <p style="text-align: center;">
            <a href="https://ferlokosamnay.netlify.app" target="_blank">Accéder à FERLOKOSAMNAY</a>
        </p>
    </div>
    <div class="footer">
        &copy; 2024 FERLOKOSAMNAY. Tous droits réservés.
    </div>
</body>
</html>
