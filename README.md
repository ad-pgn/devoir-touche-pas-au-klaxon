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

## Installation

### Prérequis

- XAMPP avec PHP 8.2 ou supérieur
- Composer
- Les extensions PHP `zip`, `pdo_mysql` et `mbstring` activées dans `php.ini`

### Mise en route

1. Cloner le dépôt dans `C:\xampp\htdocs\`
2. Installer les dépendances : `composer install`
3. Démarrer Apache et MySQL depuis le panneau XAMPP
4. Ouvrir `http://localhost/devoir-touche-pas-au-klaxon/public/`

Le module `mod_rewrite` d'Apache doit être actif : toutes les requêtes sont
redirigées vers `public/index.php` par le fichier `public/.htaccess`.

## Qualité du code

### Analyse statique

Le code est analysé par PHPStan au **niveau 8 sur 10**, sans erreur.

```bash
composer phpstan
```

Le niveau 9 imposerait d'éliminer l'usage de `mixed`, ce qui supposerait
de remplacer les tableaux associatifs retournés par PDO par des objets
typés. Ce refactoring n'a pas été retenu : il dépasse le périmètre du
projet et n'apporterait pas de garantie supplémentaire sur des données
dont la structure est fixée par le schéma de la base.

Le fichier `phpstan-bootstrap.php` déclare la constante `BASE_URL`,
définie à l'exécution dans `public/index.php` et donc invisible pour
l'analyseur statique.

### Tests unitaires

Les tests couvrent l'ensemble des opérations d'écriture en base :
création, modification et suppression des trajets et des agences, ainsi
que les contraintes de cohérence portées par le schéma.

```bash
composer test
```

Les tests s'exécutent sur une base dédiée, `touche_pas_au_klaxon_test`,
créée automatiquement au lancement à partir de `sql/01_create.sql`. La
base de l'application n'est jamais touchée.

Le schéma de test est repris du script de création de l'application afin
qu'il n'existe pas deux définitions de structure susceptibles de
diverger. Chaque test repart d'un état vierge, ce qui les rend
indépendants de leur ordre d'exécution.

## Statut

Projet en cours de développement.