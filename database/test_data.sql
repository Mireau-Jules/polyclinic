-- Données de test pour Polyclinique
USE polyclinic_db;

-- Insertion de médecins
INSERT INTO medecin (nom, prenom, sexe, adresse, telephone, email, specialisation) VALUES
('Dupont', 'Jean', 'M', '123 Rue de la Santé, Port-au-Prince', '+509 3456-7890', 'j.dupont@polyclinique.ht', 'Médecine Générale'),
('Martin', 'Marie', 'F', '456 Avenue des Médecins, Port-au-Prince', '+509 3456-7891', 'm.martin@polyclinique.ht', 'Pédiatrie'),
('Bernard', 'Pierre', 'M', '789 Boulevard Médical, Port-au-Prince', '+509 3456-7892', 'p.bernard@polyclinique.ht', 'Cardiologie'),
('Dubois', 'Sophie', 'F', '321 Rue des Spécialistes, Port-au-Prince', '+509 3456-7893', 's.dubois@polyclinique.ht', 'Dermatologie'),
('Laurent', 'Thomas', 'M', '654 Avenue de la Santé, Port-au-Prince', '+509 3456-7894', 't.laurent@polyclinique.ht', 'Orthopédie');

-- Insertion de patients
INSERT INTO patient (nom, prenom, sexe, telephone, adresse, age) VALUES
('Pierre', 'Jean', 'M', '+509 2812-3456', 'Delmas 33, Port-au-Prince', 45),
('Joseph', 'Marie', 'F', '+509 2812-3457', 'Pétion-Ville, Port-au-Prince', 32),
('Louis', 'Paul', 'M', '+509 2812-3458', 'Carrefour, Port-au-Prince', 28),
('Charles', 'Anne', 'F', '+509 2812-3459', 'Tabarre, Port-au-Prince', 55),
('Michel', 'Claudine', 'F', '+509 2812-3460', 'Croix-des-Bouquets', 41),
('François', 'Jacques', 'M', '+509 2812-3461', 'Cité Soleil, Port-au-Prince', 38),
('Antoine', 'Rose', 'F', '+509 2812-3462', 'Delmas 48, Port-au-Prince', 29),
('Etienne', 'Marc', 'M', '+509 2812-3463', 'Canapé-Vert, Port-au-Prince', 52),
('Simon', 'Lucie', 'F', '+509 2812-3464', 'Turgeau, Port-au-Prince', 36),
('David', 'Sophie', 'F', '+509 2812-3465', 'Bourdon, Port-au-Prince', 44);

-- Insertion de consultations
INSERT INTO consultation (no_dossier, code_medecin, symptome, date_consultation) VALUES
(1, 1, 'Fièvre persistante depuis 3 jours, maux de tête, fatigue générale', '2024-11-20'),
(2, 2, 'Toux sèche, légère fièvre, perte d''appétit chez l''enfant', '2024-11-21'),
(3, 3, 'Douleurs thoraciques, essoufflement lors d''efforts physiques', '2024-11-22'),
(4, 4, 'Éruption cutanée sur les bras et le dos, démangeaisons', '2024-11-23'),
(5, 1, 'Maux de ventre, nausées, diarrhée depuis 2 jours', '2024-11-24'),
(1, 3, 'Suivi consultation précédente, tension artérielle élevée', '2024-11-25'),
(6, 5, 'Douleur au genou droit après chute, difficulté à marcher', '2024-11-25'),
(7, 2, 'Vaccination de routine, contrôle de croissance', '2024-11-26'),
(8, 1, 'Diabète type 2, contrôle glycémie, renouvellement ordonnance', '2024-11-26'),
(9, 4, 'Acné sévère, demande de traitement dermatologique', '2024-11-27');

-- Insertion de prescriptions
INSERT INTO prescription (id_consultation, ordonnance) VALUES
(1, 'Paracétamol 500mg : 1 comprimé 3x/jour pendant 5 jours\nRepos au lit recommandé\nBoire beaucoup d''eau'),
(2, 'Sirop contre la toux : 5ml 3x/jour\nVitamine C : 1 comprimé/jour\nConsultation de suivi dans 5 jours si pas d''amélioration'),
(3, 'ECG prescrit\nAténolol 50mg : 1 comprimé/jour le matin\nConsultation cardiologie dans 2 semaines\nRéduire sel et effort physique intense'),
(4, 'Crème hydrocortisone 1% : application 2x/jour sur zones affectées\nAntihistaminique : 1 comprimé le soir\nÉviter allergènes identifiés'),
(5, 'Smecta : 1 sachet 3x/jour\nYaourt probiotique\nRégime léger (riz, banane)\nRéhydratation importante'),
(6, 'Amlodipine 5mg : 1 comprimé/jour\nContrôle tension dans 1 mois\nRéduire consommation de sel\nActivité physique modérée recommandée'),
(7, 'Radio genou prescrite\nIbuprofène 400mg : 1 comprimé 3x/jour si douleur\nRepos, glace, élévation jambe\nConsultation orthopédie selon résultats radio'),
(8, 'Vaccin DTC administré\nProchain vaccin dans 2 mois\nCroissance normale, continuer alimentation équilibrée'),
(9, 'Metformine 850mg : 1 comprimé 2x/jour aux repas\nContrôle glycémie à jeun chaque semaine\nConsultation suivi dans 1 mois\nRégime diabétique strict'),
(10, 'Isotrétinoïne 20mg : 1 gélule/jour pendant 6 mois\nProtection solaire SPF50+\nPrise de sang contrôle dans 1 mois\nContraception obligatoire (femmes)');

-- Affichage des statistiques
SELECT 'Données insérées avec succès !' AS message;
SELECT COUNT(*) AS 'Nombre de médecins' FROM medecin;
SELECT COUNT(*) AS 'Nombre de patients' FROM patient;
SELECT COUNT(*) AS 'Nombre de consultations' FROM consultation;
SELECT COUNT(*) AS 'Nombre de prescriptions' FROM prescription;