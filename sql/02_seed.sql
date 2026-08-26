-- =====================================================================
-- Touche pas au klaxon — Jeu d'essais
-- À exécuter après 01_create.sql
--
-- Mot de passe de tous les comptes : Klaxon2024!
-- Compte administrateur : alexandre.martin@email.fr
-- Compte utilisateur    : sophie.dubois@email.fr
-- =====================================================================

USE touche_pas_au_klaxon;

-- Vidage préalable pour permettre de rejouer le script
DELETE FROM trajet;
DELETE FROM utilisateur;
DELETE FROM agence;
ALTER TABLE trajet      AUTO_INCREMENT = 1;
ALTER TABLE utilisateur AUTO_INCREMENT = 1;
ALTER TABLE agence      AUTO_INCREMENT = 1;

-- ---------------------------------------------------------------------
-- Agences (source : annexe agences.txt)
-- ---------------------------------------------------------------------
INSERT INTO agence (nom) VALUES
    ('Paris'), ('Lyon'), ('Marseille'), ('Toulouse'),
    ('Nice'), ('Nantes'), ('Strasbourg'), ('Montpellier'),
    ('Bordeaux'), ('Lille'), ('Rennes'), ('Reims');

-- ---------------------------------------------------------------------
-- Utilisateurs (source : annexe users.txt)
-- Mots de passe hachés avec password_hash() / bcrypt.
-- Alexandre Martin est désigné administrateur.
-- ---------------------------------------------------------------------
INSERT INTO utilisateur (nom, prenom, telephone, email, mot_de_passe, role) VALUES
    ('Martin',    'Alexandre', '0612345678', 'alexandre.martin@email.fr',  '$2y$10$ly63YQEYrpXFZb6583eb5Ovtd0KActdDOIinam.1lUR.KkDDiYeoG', 'admin'),
    ('Dubois',    'Sophie',    '0698765432', 'sophie.dubois@email.fr',     '$2y$10$ly63YQEYrpXFZb6583eb5Ovtd0KActdDOIinam.1lUR.KkDDiYeoG', 'utilisateur'),
    ('Bernard',   'Julien',    '0622446688', 'julien.bernard@email.fr',    '$2y$10$ly63YQEYrpXFZb6583eb5Ovtd0KActdDOIinam.1lUR.KkDDiYeoG', 'utilisateur'),
    ('Moreau',    'Camille',   '0611223344', 'camille.moreau@email.fr',    '$2y$10$ly63YQEYrpXFZb6583eb5Ovtd0KActdDOIinam.1lUR.KkDDiYeoG', 'utilisateur'),
    ('Lefèvre',   'Lucie',     '0777889900', 'lucie.lefevre@email.fr',     '$2y$10$ly63YQEYrpXFZb6583eb5Ovtd0KActdDOIinam.1lUR.KkDDiYeoG', 'utilisateur'),
    ('Leroy',     'Thomas',    '0655443322', 'thomas.leroy@email.fr',      '$2y$10$ly63YQEYrpXFZb6583eb5Ovtd0KActdDOIinam.1lUR.KkDDiYeoG', 'utilisateur'),
    ('Roux',      'Chloé',     '0633221199', 'chloe.roux@email.fr',        '$2y$10$ly63YQEYrpXFZb6583eb5Ovtd0KActdDOIinam.1lUR.KkDDiYeoG', 'utilisateur'),
    ('Petit',     'Maxime',    '0766778899', 'maxime.petit@email.fr',      '$2y$10$ly63YQEYrpXFZb6583eb5Ovtd0KActdDOIinam.1lUR.KkDDiYeoG', 'utilisateur'),
    ('Garnier',   'Laura',     '0688776655', 'laura.garnier@email.fr',     '$2y$10$ly63YQEYrpXFZb6583eb5Ovtd0KActdDOIinam.1lUR.KkDDiYeoG', 'utilisateur'),
    ('Dupuis',    'Antoine',   '0744556677', 'antoine.dupuis@email.fr',    '$2y$10$ly63YQEYrpXFZb6583eb5Ovtd0KActdDOIinam.1lUR.KkDDiYeoG', 'utilisateur'),
    ('Lefebvre',  'Emma',      '0699887766', 'emma.lefebvre@email.fr',     '$2y$10$ly63YQEYrpXFZb6583eb5Ovtd0KActdDOIinam.1lUR.KkDDiYeoG', 'utilisateur'),
    ('Fontaine',  'Louis',     '0655667788', 'louis.fontaine@email.fr',    '$2y$10$ly63YQEYrpXFZb6583eb5Ovtd0KActdDOIinam.1lUR.KkDDiYeoG', 'utilisateur'),
    ('Chevalier', 'Clara',     '0788990011', 'clara.chevalier@email.fr',   '$2y$10$ly63YQEYrpXFZb6583eb5Ovtd0KActdDOIinam.1lUR.KkDDiYeoG', 'utilisateur'),
    ('Robin',     'Nicolas',   '0644332211', 'nicolas.robin@email.fr',     '$2y$10$ly63YQEYrpXFZb6583eb5Ovtd0KActdDOIinam.1lUR.KkDDiYeoG', 'utilisateur'),
    ('Gauthier',  'Marine',    '0677889922', 'marine.gauthier@email.fr',   '$2y$10$ly63YQEYrpXFZb6583eb5Ovtd0KActdDOIinam.1lUR.KkDDiYeoG', 'utilisateur'),
    ('Fournier',  'Pierre',    '0722334455', 'pierre.fournier@email.fr',   '$2y$10$ly63YQEYrpXFZb6583eb5Ovtd0KActdDOIinam.1lUR.KkDDiYeoG', 'utilisateur'),
    ('Girard',    'Sarah',     '0688665544', 'sarah.girard@email.fr',      '$2y$10$ly63YQEYrpXFZb6583eb5Ovtd0KActdDOIinam.1lUR.KkDDiYeoG', 'utilisateur'),
    ('Lambert',   'Hugo',      '0611223366', 'hugo.lambert@email.fr',      '$2y$10$ly63YQEYrpXFZb6583eb5Ovtd0KActdDOIinam.1lUR.KkDDiYeoG', 'utilisateur'),
    ('Masson',    'Julie',     '0733445566', 'julie.masson@email.fr',      '$2y$10$ly63YQEYrpXFZb6583eb5Ovtd0KActdDOIinam.1lUR.KkDDiYeoG', 'utilisateur'),
    ('Henry',     'Arthur',    '0666554433', 'arthur.henry@email.fr',      '$2y$10$ly63YQEYrpXFZb6583eb5Ovtd0KActdDOIinam.1lUR.KkDDiYeoG', 'utilisateur');

-- ---------------------------------------------------------------------
-- Trajets
-- Les dates sont calculées relativement à la date d'exécution du script
-- afin que le jeu d'essais reste pertinent quel que soit le moment où
-- il est rejoué. Le jeu couvre les cas à tester : trajets à venir avec
-- places, trajets complets, trajets passés.
-- ---------------------------------------------------------------------

-- Trajets à venir avec places disponibles (visibles sur la page d'accueil)
INSERT INTO trajet (gdh_depart, gdh_arrivee, places_total, places_disponibles,
                    utilisateur_id, agence_depart_id, agence_arrivee_id) VALUES
    (DATE_ADD(NOW(), INTERVAL 2 DAY),  DATE_ADD(NOW(), INTERVAL 2 DAY)  + INTERVAL 5 HOUR, 4, 3, 1,  1, 2),
    (DATE_ADD(NOW(), INTERVAL 3 DAY),  DATE_ADD(NOW(), INTERVAL 3 DAY)  + INTERVAL 3 HOUR, 3, 1, 2,  2, 3),
    (DATE_ADD(NOW(), INTERVAL 5 DAY),  DATE_ADD(NOW(), INTERVAL 5 DAY)  + INTERVAL 7 HOUR, 5, 4, 3,  1, 9),
    (DATE_ADD(NOW(), INTERVAL 6 DAY),  DATE_ADD(NOW(), INTERVAL 6 DAY)  + INTERVAL 2 HOUR, 4, 2, 4,  10, 1),
    (DATE_ADD(NOW(), INTERVAL 8 DAY),  DATE_ADD(NOW(), INTERVAL 8 DAY)  + INTERVAL 4 HOUR, 2, 1, 5,  6, 11),
    (DATE_ADD(NOW(), INTERVAL 10 DAY), DATE_ADD(NOW(), INTERVAL 10 DAY) + INTERVAL 6 HOUR, 4, 4, 6,  4, 8),
    (DATE_ADD(NOW(), INTERVAL 12 DAY), DATE_ADD(NOW(), INTERVAL 12 DAY) + INTERVAL 3 HOUR, 3, 2, 7,  5, 3),
    (DATE_ADD(NOW(), INTERVAL 15 DAY), DATE_ADD(NOW(), INTERVAL 15 DAY) + INTERVAL 8 HOUR, 6, 5, 8,  7, 1);

-- Trajets complets (aucune place restante : absents de la page d'accueil)
INSERT INTO trajet (gdh_depart, gdh_arrivee, places_total, places_disponibles,
                    utilisateur_id, agence_depart_id, agence_arrivee_id) VALUES
    (DATE_ADD(NOW(), INTERVAL 4 DAY),  DATE_ADD(NOW(), INTERVAL 4 DAY)  + INTERVAL 5 HOUR, 3, 0, 9,  1, 12),
    (DATE_ADD(NOW(), INTERVAL 7 DAY),  DATE_ADD(NOW(), INTERVAL 7 DAY)  + INTERVAL 4 HOUR, 4, 0, 10, 2, 5);

-- Trajets passés (absents de la page d'accueil)
INSERT INTO trajet (gdh_depart, gdh_arrivee, places_total, places_disponibles,
                    utilisateur_id, agence_depart_id, agence_arrivee_id) VALUES
    (DATE_SUB(NOW(), INTERVAL 10 DAY), DATE_SUB(NOW(), INTERVAL 10 DAY) + INTERVAL 5 HOUR, 4, 2, 1, 1, 2),
    (DATE_SUB(NOW(), INTERVAL 5 DAY),  DATE_SUB(NOW(), INTERVAL 5 DAY)  + INTERVAL 3 HOUR, 3, 1, 2, 3, 4);