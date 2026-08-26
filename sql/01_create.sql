-- =====================================================================
-- Touche pas au klaxon — Script de création de la base de données
-- SGBD cible : MySQL 5.7+ / MariaDB 10.2+
-- =====================================================================

CREATE DATABASE IF NOT EXISTS touche_pas_au_klaxon
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE touche_pas_au_klaxon;

-- Suppression dans l'ordre inverse des dépendances
DROP TABLE IF EXISTS trajet;
DROP TABLE IF EXISTS utilisateur;
DROP TABLE IF EXISTS agence;

-- ---------------------------------------------------------------------
-- Table utilisateur
-- Alimentée depuis l'extraction du système RH : l'application ne prévoit
-- ni création, ni modification, ni suppression d'employés.
-- ---------------------------------------------------------------------
CREATE TABLE utilisateur (
    id           INT          NOT NULL AUTO_INCREMENT,
    nom          VARCHAR(100) NOT NULL,
    prenom       VARCHAR(100) NOT NULL,
    email        VARCHAR(180) NOT NULL,
    telephone    VARCHAR(20)  NOT NULL,
    mot_de_passe VARCHAR(255) NOT NULL,
    role         VARCHAR(20)  NOT NULL DEFAULT 'utilisateur',
    PRIMARY KEY (id),
    CONSTRAINT uq_utilisateur_email UNIQUE (email),
    CONSTRAINT ck_utilisateur_role  CHECK (role IN ('utilisateur', 'admin'))
) ENGINE = InnoDB;

-- ---------------------------------------------------------------------
-- Table agence
-- Liste des villes d'implantation. Seul l'administrateur peut la modifier.
-- ---------------------------------------------------------------------
CREATE TABLE agence (
    id  INT          NOT NULL AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL,
    PRIMARY KEY (id),
    CONSTRAINT uq_agence_nom UNIQUE (nom)
) ENGINE = InnoDB;

-- ---------------------------------------------------------------------
-- Table trajet
-- Porte les trois clés étrangères issues des associations (1,1) du MCD.
-- ---------------------------------------------------------------------
CREATE TABLE trajet (
    id                 INT      NOT NULL AUTO_INCREMENT,
    gdh_depart         DATETIME NOT NULL,
    gdh_arrivee        DATETIME NOT NULL,
    places_total       INT      NOT NULL,
    places_disponibles INT      NOT NULL,
    utilisateur_id     INT      NOT NULL,
    agence_depart_id   INT      NOT NULL,
    agence_arrivee_id  INT      NOT NULL,

    PRIMARY KEY (id),

    CONSTRAINT fk_trajet_utilisateur
        FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)
        ON DELETE CASCADE,

    CONSTRAINT fk_trajet_agence_depart
        FOREIGN KEY (agence_depart_id) REFERENCES agence (id)
        ON DELETE RESTRICT,

    CONSTRAINT fk_trajet_agence_arrivee
        FOREIGN KEY (agence_arrivee_id) REFERENCES agence (id)
        ON DELETE RESTRICT,

    -- Contrôles de cohérence exigés par le cahier des charges
    CONSTRAINT ck_trajet_agences_differentes
        CHECK (agence_depart_id <> agence_arrivee_id),
    CONSTRAINT ck_trajet_chronologie
        CHECK (gdh_arrivee > gdh_depart),
    CONSTRAINT ck_trajet_places_total
        CHECK (places_total > 0),
    CONSTRAINT ck_trajet_places_disponibles
        CHECK (places_disponibles >= 0 AND places_disponibles <= places_total)
) ENGINE = InnoDB;

-- Index sur la date de départ : la page d'accueil trie et filtre dessus
CREATE INDEX idx_trajet_gdh_depart ON trajet (gdh_depart);