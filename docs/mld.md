# MLD — Touche pas au klaxon

```
UTILISATEUR = (#id COUNTER, nom VARCHAR(100), prenom VARCHAR(100),
               email VARCHAR(180), telephone VARCHAR(20),
               mot_de_passe VARCHAR(255), role VARCHAR(20));

AGENCE = (#id COUNTER, nom VARCHAR(100));

TRAJET = (#id COUNTER, gdh_depart DATETIME, gdh_arrivee DATETIME,
          places_total INT, places_disponibles INT,
          #utilisateur_id => UTILISATEUR,
          #agence_depart_id => AGENCE,
          #agence_arrivee_id => AGENCE);
```

Les trois associations du MCD sont de type (1,1) — (0,n). La clé étrangère
est donc placée du côté (1,1), c'est-à-dire dans `TRAJET` pour les trois.
Aucune table d'association n'est nécessaire.