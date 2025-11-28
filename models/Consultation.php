<?php
class Consultation {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function create($data) {
        $sql = "INSERT INTO consultation (no_dossier, code_medecin, symptome, date_consultation) 
                VALUES (:no_dossier, :code_medecin, :symptome, :date_consultation)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($data);
    }

    public function getAll() {
        $sql = "SELECT c.*, p.nom as patient_nom, p.prenom as patient_prenom,
                       m.nom as medecin_nom, m.prenom as medecin_prenom, m.specialisation
                FROM consultation c
                JOIN patient p ON c.no_dossier = p.no_dossier
                JOIN medecin m ON c.code_medecin = m.code_medecin
                ORDER BY c.date_consultation DESC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }

    public function getByPatient($no_dossier) {
        $sql = "SELECT c.*, m.nom as medecin_nom, m.prenom as medecin_prenom, m.specialisation
                FROM consultation c
                JOIN medecin m ON c.code_medecin = m.code_medecin
                WHERE c.no_dossier = :no_dossier
                ORDER BY c.date_consultation DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['no_dossier' => $no_dossier]);
        return $stmt->fetchAll();
    }

    public function getById($id_consultation) {
        $sql = "SELECT c.*, p.nom as patient_nom, p.prenom as patient_prenom,
                       m.nom as medecin_nom, m.prenom as medecin_prenom
                FROM consultation c
                JOIN patient p ON c.no_dossier = p.no_dossier
                JOIN medecin m ON c.code_medecin = m.code_medecin
                WHERE c.id_consultation = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id_consultation]);
        return $stmt->fetch();
    }

    public function count() {
        $sql = "SELECT COUNT(*) as total FROM consultation";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetch()['total'];
    }

    public function getLastInsertId() {
        return $this->pdo->lastInsertId();
    }
}
?>
