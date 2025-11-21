-- Polyclinic Database Schema
-- Run this in phpMyAdmin or MySQL

CREATE DATABASE IF NOT EXISTS polyclinic_db;
USE polyclinic_db;

-- Table Patient
CREATE TABLE patient (
    no_dossier INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    sexe ENUM('M', 'F') NOT NULL,
    telephone VARCHAR(20),
    adresse VARCHAR(255),
    age INT
);

-- Table Medecin
CREATE TABLE medecin (
    code_medecin INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    sexe ENUM('M', 'F') NOT NULL,
    adresse VARCHAR(255),
    telephone VARCHAR(20),
    email VARCHAR(100),
    specialisation VARCHAR(100)
);

-- Table Consultation
CREATE TABLE consultation (
    id_consultation INT AUTO_INCREMENT PRIMARY KEY,
    no_dossier INT NOT NULL,
    code_medecin INT NOT NULL,
    symptome TEXT,
    date_consultation DATE NOT NULL,
    FOREIGN KEY (no_dossier) REFERENCES patient(no_dossier) ON DELETE CASCADE,
    FOREIGN KEY (code_medecin) REFERENCES medecin(code_medecin) ON DELETE CASCADE
);

-- Table Prescription
CREATE TABLE prescription (
    id_prescription INT AUTO_INCREMENT PRIMARY KEY,
    id_consultation INT NOT NULL,
    ordonnance TEXT NOT NULL,
    FOREIGN KEY (id_consultation) REFERENCES consultation(id_consultation) ON DELETE CASCADE
);
