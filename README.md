# Toubeelib2
___
### 📦 Dépôt GitHub
https://github.com/felixbrun0/toubeelib2_BRUN_MALGRAS_MARTINS_DWM2.git
___
### 👥 Participants :
- BRUN Félix
- MALGRAS MARTINS Nolan
___

## Fonctionnalités du Projet

### 1. Gestion des Rendez-vous
- ✨ Création de rendez-vous médicaux
- 📅 Consultation des rendez-vous
- 🔄 Modification et annulation de rendez-vous
- ⏰ Gestion des créneaux horaires

### 2. Gestion des Praticiens
- 👨‍⚕️ Liste des praticiens disponibles
- ℹ️ Informations détaillées sur chaque praticien
- 🏥 Gestion des spécialités médicales
- 📊 Planning des praticiens

### 3. Authentification et Sécurité
- 📝 Inscription des utilisateurs
- 🔐 Connexion sécurisée
- 🎟️ Gestion des tokens JWT
- 🔄 Système de refresh token

## Architecture Technique

### 1. Architecture Microservices
- **Gateway Service** (Port 8081)
  - Point d'entrée unique de l'application
  - Routage des requêtes vers les services appropriés

- **Service Praticiens** (Port 6089)
  - Gestion des données des praticiens
  - API dédiée aux praticiens

- **Service Rendez-vous** (Port 6088)
  - Gestion des rendez-vous
  - Planification et disponibilités

- **Service Authentification** (Port 6090)
  - Gestion des utilisateurs
  - Génération et validation des tokens JWT

### 2. Base de Données
- PostgreSQL pour chaque service :
  - `toubeelib.dbpraticien` : Données des praticiens
  - `toubeelib.dbrdvs` : Gestion des rendez-vous
  - `toubeelib.dbauth` : Authentification et utilisateurs

### 3. Sécurité
- **JWT (JSON Web Tokens)**
  - Authentification stateless
  - Durée de validité configurable
  - Système de refresh token

- **Middleware de Sécurité**
  - Validation des tokens
  - Gestion des autorisations
  - Protection des routes sensibles

### 4. Technologies Utilisées
- **Backend** : PHP 8.3
- **Framework** : Slim Framework
- **Conteneurisation** : Docker
- **Base de données** : PostgreSQL
- **Réseau** : Bridge network Docker personnalisé

### 5. Caractéristiques Techniques
- ✅ Architecture RESTful
- 🐳 Conteneurisation complète
- ⚙️ Configuration par variables d'environnement
- 🌐 Gestion des CORS
- 📝 Logging et gestion des erreurs

### 6. Développement et Déploiement
- 🐳 Environnement Docker complet
- 📄 Configuration flexible via fichiers .env
- 🔌 Ports configurables pour chaque service
- 💾 Volumes Docker pour le développement

Cette architecture permet une scalabilité horizontale et une maintenance facilitée grâce à la séparation des responsabilités entre les différents services.

## Scripts SQL des Bases de Données

### 1. Base de données d'authentification (auth)
```sql
CREATE TABLE IF NOT EXISTS users (
    id SERIAL PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(50) NOT NULL DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS refresh_tokens (
    id SERIAL PRIMARY KEY,
    user_id INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    token VARCHAR(255) NOT NULL UNIQUE,
    expires_at TIMESTAMP NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Utilisateur test (password: password)
INSERT INTO users (email, password, role) VALUES 
('test@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user');
```

### 2. Base de données des praticiens (praticiens)
```sql
CREATE TABLE IF NOT EXISTS specialite (
    id VARCHAR(36) PRIMARY KEY,
    label VARCHAR(100) NOT NULL,
    description TEXT
);

CREATE TABLE IF NOT EXISTS praticien (
    id VARCHAR(36) PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    adresse TEXT,
    telephone VARCHAR(20),
    specialite_id VARCHAR(36) REFERENCES specialite(id)
);

-- Données de test
INSERT INTO specialite (id, label, description) VALUES
    ('1', 'Généraliste', 'Médecin généraliste'),
    ('2', 'Dentiste', 'Chirurgien-dentiste'),
    ('3', 'Ophtalmologue', 'Spécialiste des yeux');

INSERT INTO praticien (id, nom, prenom, adresse, telephone, specialite_id) VALUES
    ('1', 'Dupont', 'Jean', '1 rue de la Paix, Paris', '0123456789', '1'),
    ('2', 'Martin', 'Sophie', '2 avenue des Champs-Élysées, Paris', '0234567890', '2'),
    ('3', 'Bernard', 'Pierre', '3 boulevard Haussmann, Paris', '0345678901', '3');
```

### 3. Base de données des rendez-vous (rdv)
```sql
CREATE TABLE IF NOT EXISTS rdv (
    id VARCHAR(36) PRIMARY KEY,
    date_rdv TIMESTAMP NOT NULL,
    duree INTEGER NOT NULL, -- durée en minutes
    praticien_id VARCHAR(36) NOT NULL,
    patient_id VARCHAR(36) NOT NULL,
    motif TEXT
);

-- Données de test
INSERT INTO rdv (id, date_rdv, duree, praticien_id, patient_id, motif) VALUES
    ('1', '2025-02-01 09:00:00', 30, '1', 'patient1', 'Consultation de routine'),
    ('2', '2025-02-01 10:00:00', 45, '2', 'patient2', 'Détartrage'),
    ('3', '2025-02-01 11:00:00', 60, '3', 'patient3', 'Examen de la vue');
```

Pour initialiser les bases de données :
1. Créez les bases de données respectives
2. Exécutez les scripts SQL dans l'ordre suivant :
   - Script d'authentification
   - Script des praticiens
   - Script des rendez-vous
3. Les données de test sont déjà incluses dans les scripts

## Installation et Démarrage

1. Cloner le repository
```bash
  git clone [url-du-repo]
```

2. Copier et configurer les fichiers d'environnement
```bash
  cp toubeelib.env.dist toubeelib.env
  cp toubeelibdb.env.dist toubeelibdb.env
```

3. Lancer les conteneurs Docker
```bash
  docker-compose up -d
```

4. Les services seront disponibles aux ports suivants :
- Gateway : http://localhost:8081
- Service Praticiens : http://localhost:6089
- Service Rendez-vous : http://localhost:6088
- Service Authentification : http://localhost:6090