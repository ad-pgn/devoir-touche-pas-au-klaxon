# Touche pas au klaxon

Application web de covoiturage inter-sites, développée dans le cadre de la
formation Développeur Web du Centre Européen de Formation.

L'application permet aux employés d'une entreprise multi-sites de diffuser
les trajets qu'ils prévoient entre les différentes agences, afin de
favoriser le covoiturage et de réduire le nombre de véhicules faiblement
occupés sur un même itinéraire.

## Fonctionnalités

### Visiteur

Consultation de la liste des trajets à venir disposant encore de places
libres, triés par date de départ croissante. Les trajets passés et
complets n'apparaissent pas.

### Employé connecté

- Consultation du détail d'un trajet : identité, téléphone et adresse
  email du conducteur, nombre total de places
- Proposition d'un nouveau trajet
- Modification et suppression de ses propres trajets

### Administrateur

- Tableau de bord regroupant les indicateurs de l'application
- Consultation de la liste des utilisateurs
- Création, modification et suppression des agences
- Consultation de tous les trajets, y compris passés et complets, et
  suppression de n'importe lequel d'entre eux

Les employés sont importés du système RH : l'application ne prévoit ni
leur création, ni leur modification, ni leur suppression.

## Stack technique

- PHP 8.2, architecture MVC sans framework
- MySQL / MariaDB, accès par PDO en requêtes préparées
- Routeur `izniburak/router`
- Bootstrap 5 et Sass
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
- Node.js et npm, uniquement pour recompiler les styles
- Les extensions PHP `zip`, `pdo_mysql` et `mbstring` activées dans
  `php.ini`

L'extension `zip` est désactivée par défaut dans XAMPP et bloque
l'installation des dépendances Composer. Pour l'activer, ouvrir
`C:\xampp\php\php.ini`, retirer le point-virgule devant `;extension=zip`,
enregistrer et redémarrer le terminal.

### Mise en route

1. Cloner le dépôt dans `C:\xampp\htdocs\`
2. Installer les dépendances PHP :

```bash
   composer install
```

3. Démarrer Apache et MySQL depuis le panneau XAMPP
4. Créer et alimenter la base de données via phpMyAdmin, en exécutant
   dans l'ordre :

   - `sql/01_create.sql` — création de la base et des tables
   - `sql/02_seed.sql` — jeu d'essais

   Le premier script contient son propre `CREATE DATABASE` : il doit être
   exécuté depuis la page d'accueil de phpMyAdmin, sans base sélectionnée.

5. Ouvrir `http://localhost/devoir-touche-pas-au-klaxon/public/`

Le module `mod_rewrite` d'Apache doit être actif : toutes les requêtes sont
redirigées vers `public/index.php` par le fichier `public/.htaccess`.

### Configuration

Les paramètres de connexion se trouvent dans `config/config.php` et
correspondent à une installation XAMPP standard. Pour les adapter sans
modifier ce fichier versionné, créer un fichier `config/config.local.php`
retournant un tableau de même structure : ses valeurs écrasent celles par
défaut. Ce fichier est ignoré par Git.

```php
<?php

return [
    'db' => [
        'port' => 3307,
        'pass' => 'votre_mot_de_passe',
    ],
];
```

### Recompiler les styles

Le fichier `public/assets/css/style.css` est versionné : l'application
s'affiche correctement sans étape supplémentaire. Pour modifier les
styles :

```bash
npm install
npm run css
```

La palette de couleurs imposée est appliquée en surchargeant les variables
Bootstrap dans `scss/main.scss`, avant l'import du framework. Changer de
palette ne demande donc que de modifier six valeurs.

## Comptes de démonstration

Le mot de passe est identique pour l'ensemble des comptes du jeu d'essais.

| Rôle | Adresse email | Mot de passe |
|---|---|---|
| Administrateur | `alexandre.martin@email.fr` | `Klaxon2024!` |
| Utilisateur | `sophie.dubois@email.fr` | `Klaxon2024!` |

Les dix-huit autres employés du jeu d'essais utilisent le même mot de
passe.

## Qualité du code

### Analyse statique

Le code est analysé par PHPStan au **niveau 8 sur 10**, sans erreur.

```bash
composer phpstan
```

Le niveau 9 imposerait d'éliminer l'usage de `mixed`, ce qui supposerait de
remplacer les tableaux associatifs retournés par PDO par des objets typés.
Ce refactoring n'a pas été retenu : il dépasse le périmètre du projet et
n'apporterait pas de garantie supplémentaire sur des données dont la
structure est fixée par le schéma de la base.

Le fichier `phpstan-bootstrap.php` déclare la constante `BASE_URL`, définie
à l'exécution dans `public/index.php` et donc invisible pour l'analyseur.

### Tests unitaires

Les tests couvrent l'ensemble des opérations d'écriture en base : création,
modification et suppression des trajets et des agences, ainsi que les
contraintes de cohérence portées par le schéma.

```bash
composer test
```

Les tests s'exécutent sur une base dédiée, `touche_pas_au_klaxon_test`,
créée automatiquement au lancement à partir de `sql/01_create.sql`. La base
de l'application n'est jamais touchée.

Le schéma de test est repris du script de création de l'application afin
qu'il n'existe pas deux définitions de structure susceptibles de diverger.
Chaque test repart d'un état vierge, ce qui les rend indépendants de leur
ordre d'exécution.

### Documentation

L'ensemble du code est commenté au format DocBlock : description, `@param`,
`@return` et `@throws` sur chaque méthode, en-tête sur chaque fichier.

## Sécurité

- Mots de passe hachés avec `password_hash()` et vérifiés par
  `password_verify()`
- Requêtes préparées systématiques, avec `PDO::ATTR_EMULATE_PREPARES`
  désactivé afin que la préparation soit réellement effectuée par le SGBD
- Échappement systématique des données affichées via `htmlspecialchars()`
- Régénération de l'identifiant de session à la connexion, contre la
  fixation de session
- Opérations d'écriture exposées en POST uniquement
- Contrôles d'accès vérifiés au début de chaque action de contrôleur, une
  requête ne se fiant jamais aux vérifications de l'écran précédent

Une protection anti-CSRF par jeton a été identifiée comme évolution
souhaitable mais n'est pas implémentée dans cette version.

## Base de données

Le modèle conceptuel se trouve dans `docs/MCD_devoir_php.png` et le modèle
logique dans `docs/mld.md`.

Le jeu d'essais positionne les trajets à des dates relatives à son
exécution. Les trajets « à venir » deviennent donc progressivement des
trajets passés : rejouer `sql/02_seed.sql` remet le jeu à niveau.

## Auteur

Projet réalisé par ad-pgn dans le cadre de la formation Développeur Web du Centre Européen de Formation.