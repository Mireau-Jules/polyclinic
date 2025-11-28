<?php
class Patient {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Créer un nouveau patient
    public function create($data) {
        $sql = "INSERT INTO patient (nom, prenom, sexe, telephone, adresse, age) 
                VALUES (:nom, :prenom, :sexe, :telephone, :adresse, :age)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($data);
    }

    // Obtenir tous les patients
    public function getAll() {
        $sql = "SELECT * FROM patient ORDER BY no_dossier DESC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }

    // Obtenir un patient par numéro de dossier
    public function getById($no_dossier) {
        $sql = "SELECT * FROM patient WHERE no_dossier = :no_dossier";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['no_dossier' => $no_dossier]);
        return $stmt->fetch();
    }

    // Rechercher des patients
    public function search($keyword) {
        $sql = "SELECT * FROM patient 
                WHERE nom LIKE :keyword 
                OR prenom LIKE :keyword 
                OR telephone LIKE :keyword 
                OR no_dossier LIKE :keyword
                ORDER BY no_dossier DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['keyword' => "%$keyword%"]);
        return $stmt->fetchAll();
    }

    // Mettre à jour un patient
    public function update($no_dossier, $data) {
        $sql = "UPDATE patient 
                SET nom = :nom, prenom = :prenom, sexe = :sexe, 
                    telephone = :telephone, adresse = :adresse, age = :age 
                WHERE no_dossier = :no_dossier";
        $data['no_dossier'] = $no_dossier;
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($data);
    }

    // Supprimer un patient
    public function delete($no_dossier) {
        $sql = "DELETE FROM patient WHERE no_dossier = :no_dossier";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['no_dossier' => $no_dossier]);
    }

    // Compter les patients
    public function count() {
        $sql = "SELECT COUNT(*) as total FROM patient";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetch()['total'];
    }
}
?>