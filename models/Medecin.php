<?php
class Medecin {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function create($data) {
        $sql = "INSERT INTO medecin (nom, prenom, sexe, adresse, telephone, email, specialisation) 
                VALUES (:nom, :prenom, :sexe, :adresse, :telephone, :email, :specialisation)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($data);
    }

    public function getAll() {
        $sql = "SELECT * FROM medecin ORDER BY nom, prenom";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }

    public function getById($code_medecin) {
        $sql = "SELECT * FROM medecin WHERE code_medecin = :code_medecin";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['code_medecin' => $code_medecin]);
        return $stmt->fetch();
    }

    public function count() {
        $sql = "SELECT COUNT(*) as total FROM medecin";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetch()['total'];
    }
}
?>
