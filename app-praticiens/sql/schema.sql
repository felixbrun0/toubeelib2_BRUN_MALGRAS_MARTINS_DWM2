-- Table des spécialités
CREATE TABLE IF NOT EXISTS specialite (
    id VARCHAR(36) PRIMARY KEY,
    label VARCHAR(100) NOT NULL,
    description TEXT
);

-- Table des praticiens
CREATE TABLE IF NOT EXISTS praticien (
    id VARCHAR(36) PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    adresse TEXT,
    telephone VARCHAR(20),
    specialite_id VARCHAR(36) REFERENCES specialite(id)
);

-- Insertion de quelques spécialités
INSERT INTO specialite (id, label, description) VALUES
    ('1', 'Généraliste', 'Médecin généraliste'),
    ('2', 'Dentiste', 'Chirurgien-dentiste'),
    ('3', 'Ophtalmologue', 'Spécialiste des yeux');

-- Insertion de quelques praticiens
INSERT INTO praticien (id, nom, prenom, adresse, telephone, specialite_id) VALUES
    ('1', 'Dupont', 'Jean', '1 rue de la Paix, Paris', '0123456789', '1'),
    ('2', 'Martin', 'Sophie', '2 avenue des Champs-Élysées, Paris', '0234567890', '2'),
    ('3', 'Bernard', 'Pierre', '3 boulevard Haussmann, Paris', '0345678901', '3');
