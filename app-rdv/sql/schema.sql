-- Table des rendez-vous
CREATE TABLE IF NOT EXISTS rdv (
    id VARCHAR(36) PRIMARY KEY,
    date_rdv TIMESTAMP NOT NULL,
    duree INTEGER NOT NULL, -- durée en minutes
    praticien_id VARCHAR(36) NOT NULL,
    patient_id VARCHAR(36) NOT NULL,
    motif TEXT
);

-- Insertion de quelques rendez-vous de test
INSERT INTO rdv (id, date_rdv, duree, praticien_id, patient_id, motif) VALUES
    ('1', '2025-02-01 09:00:00', 30, '1', 'patient1', 'Consultation de routine'),
    ('2', '2025-02-01 10:00:00', 45, '2', 'patient2', 'Détartrage'),
    ('3', '2025-02-01 11:00:00', 60, '3', 'patient3', 'Examen de la vue');
