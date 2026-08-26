# Touche pas au klaxon

Application web de covoiturage inter-sites, développée dans le cadre
de la formation Développeur Web du Centre Européen de Formation.

## Stack technique

- PHP natif, architecture MVC
- MySQL / MariaDB
- Bootstrap 5 + Sass
- Composer, PHPStan, PHPUnit

## Architecture

Le projet suit une architecture MVC. Le point d'entrée unique est
`public/index.php` : c'est le seul répertoire exposé par le serveur web.

```
app/
├── Controller/    Contrôleurs : reçoivent la requête, appellent les modèles,
│                  transmettent les données aux vues
└── Model/         Modèles : accès aux données et règles métier
core/              Socle réutilisable : connexion PDO, modèle générique,
                   contrôleur de base, session, messages flash
config/            Configuration de l'application et définition des routes
public/            Racine web : point d'entrée et ressources statiques
└── assets/css/    Feuilles de style compilées depuis Sass
scss/              Sources Sass, dont la surcharge des variables Bootstrap
templates/         Vues : layout, partials et pages
sql/               Script de création et script d'alimentation de la base
tests/             Tests unitaires PHPUnit
docs/              MCD, MLD et documents du dossier de rendu
```

Les namespaces suivent la norme PSR-4 : `App\` pointe vers `app/` et `Core\`
vers `core/`. La classe `App\Controller\HomeController` correspond donc au
fichier `app/Controller/HomeController.php`.

## Statut

Projet en cours de développement.