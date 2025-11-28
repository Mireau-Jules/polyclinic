<?php
class Prescription {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Créer une prescription
    public function create($data) {
        $sql = "INSERT INTO prescription (id_consultation, ordonnance) 
                VALUES (:id_consultation, :ordonnance)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($data);
    }

    // Obtenir les prescriptions d'une consultation
    public function getByConsultation($id_consultation) {
        $sql = "SELECT * FROM prescription WHERE id_consultation = :id_consultation";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id_consultation' => $id_consultation]);
        return $stmt->fetchAll();
    }

    // Obtenir une prescription par ID
    public function getById($id_prescription) {
        $sql = "SELECT * FROM prescription WHERE id_prescription = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id_prescription]);
        return $stmt->fetch();
    }
}
?>
