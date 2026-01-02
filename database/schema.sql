-- Schema mis à jour avec authentification et rendez-vous
USE polyclinic_db;

-- Ajouter le mot de passe aux médecins (pour le login)
ALTER TABLE medecin 
ADD COLUMN password VARCHAR(255) DEFAULT NULL,
ADD COLUMN is_active TINYINT(1) DEFAULT 1,
ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP;

-- Table pour les rendez-vous
CREATE TABLE IF NOT EXISTS rendez_vous (
    id_rdv INT AUTO_INCREMENT PRIMARY KEY,
    nom_patient VARCHAR(100) NOT NULL,
    prenom_patient VARCHAR(100) NOT NULL,
    telephone VARCHAR(20) NOT NULL,
    email VARCHAR(100),
    code_medecin INT NOT NULL,
    date_rdv DATE NOT NULL,
    heure_rdv TIME NOT NULL,
    motif TEXT,
    statut ENUM('en_attente', 'confirme', 'annule', 'termine') DEFAULT 'en_attente',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (code_medecin) REFERENCES medecin(code_medecin) ON DELETE CASCADE
);

-- Créer un médecin admin par défaut
-- Email: admin@polyclinique.ht
-- Password: admin123 (à changer après première connexion)
INSERT INTO medecin (nom, prenom, sexe, adresse, telephone, email, specialisation, password, is_active)
VALUES ('Admin', 'Système', 'M', 'Administration', '+509 0000-0000', 'admin@polyclinique.ht', 'Administration', 
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1)
ON DUPLICATE KEY UPDATE email = email;

-- Note: Le mot de passe hashé correspond à "admin123"

SELECT 'Schema mis à jour avec succès!' AS message;