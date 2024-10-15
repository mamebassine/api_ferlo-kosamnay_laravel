<!DOCTYPE html>
<html>
<head>
    <title>Bienvenue</title>
</head>
<body>
    <h1>Bienvenue, {{ $representant->nom_complet }}</h1>
    <p>Votre compte de représentant a été créé avec succès sur notre plateforme.</p>
    <p>Voici vos identifiants de connexion :</p>
    <ul>
        <li>Email: {{ $email }}</li>
        <li>Mot de passe: {{ $password }}</li>
        
    </ul>
    <p>Veuillez vous connecter et changer votre mot de passe après votre première connexion.</p>
    <p>Merci de faire partie de notre communauté !</p>

    <p>Vous pouvez accéder à notre site via ce lien : <a href="https://ferlokosamnay.netlify.app">FERLOKOSAMNAY</a>.</p>

</body>
</html>
