<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Valeurs par défaut d'authentification
    |--------------------------------------------------------------------------
    |
    | Cette option définit le "guard" d'authentification par défaut et le
    | "broker" de réinitialisation de mot de passe pour votre application.
    | Vous pouvez modifier ces valeurs selon vos besoins, mais elles sont
    | idéales pour la plupart des applications.
    |
    */

    'defaults' => [
        'guard' => env('AUTH_GUARD', 'web'),
        'passwords' => env('AUTH_PASSWORD_BROKER', 'users'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Guards d'authentification
    |--------------------------------------------------------------------------
    |
    | Vous pouvez définir chaque guard d'authentification pour votre application.
    | Bien sûr, une configuration par défaut a été définie pour vous qui utilise
    | le stockage de session plus le fournisseur d'utilisateurs Eloquent.
    |
    | Tous les guards d'authentification ont un fournisseur d'utilisateurs,
    | qui définit comment les utilisateurs sont réellement récupérés
    | dans votre base de données ou tout autre système de stockage utilisé
    | par l'application. En général, Eloquent est utilisé.
    |
    | Supports : "session", "jwt"
    |
    */

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

        'api' => [
        'driver' => 'jwt', // ou 'passport'/'sanctum' si vous utilisez Laravel Passport ou Sanctum
        'provider' => 'users',
    ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Fournisseurs d'utilisateurs
    |--------------------------------------------------------------------------
    |
    | Tous les guards d'authentification ont un fournisseur d'utilisateurs,
    | qui définit comment les utilisateurs sont récupérés de votre base de
    | données ou tout autre système de stockage utilisé par l'application.
    | En général, Eloquent est utilisé.
    |
    | Si vous avez plusieurs tables ou modèles d'utilisateurs, vous pouvez
    | configurer plusieurs fournisseurs pour représenter le modèle/table.
    | Ces fournisseurs peuvent ensuite être assignés à d'autres guards
    | d'authentification que vous avez définis.
    |
    | Supports : "database", "eloquent"
    |
    */

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => env('AUTH_MODEL', App\Models\User::class),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Réinitialisation des mots de passe
    |--------------------------------------------------------------------------
    |
    | Ces options de configuration spécifient le comportement de la
    | fonctionnalité de réinitialisation de mot de passe de Laravel,
    | y compris la table utilisée pour le stockage des tokens et le
    | fournisseur d'utilisateurs qui est invoqué pour récupérer les utilisateurs.
    |
    | Le temps d'expiration est le nombre de minutes pendant lequel chaque
    | token de réinitialisation sera considéré comme valide. Cette fonctionnalité
    | de sécurité maintient les tokens à durée limitée afin qu'ils aient moins
    | de temps à être devinés. Vous pouvez modifier cela si nécessaire.
    |
    | Le paramètre de throttling est le nombre de secondes qu'un utilisateur
    | doit attendre avant de générer d'autres tokens de réinitialisation de mot
    | de passe. Cela empêche l'utilisateur de générer rapidement un très grand
    | nombre de tokens de réinitialisation de mot de passe.
    |
    */

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_reset_tokens'),
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Délai d'expiration de confirmation de mot de passe
    |--------------------------------------------------------------------------
    |
    | Ici, vous pouvez définir le nombre de secondes avant qu'une fenêtre de
    | confirmation de mot de passe n'expire et que les utilisateurs soient
    | invités à saisir à nouveau leur mot de passe via l'écran de confirmation.
    | Par défaut, le délai d'expiration dure trois heures.
    |
    */

    'password_timeout' => env('AUTH_PASSWORD_TIMEOUT', 10800),

];
