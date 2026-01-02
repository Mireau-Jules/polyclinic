<?php
class RendezVous {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function create($data) {
        $sql = "INSERT INTO rendez_vous (nom_patient, prenom_patient, telephone, email, 
                code_medecin, date_rdv, heure_rdv, motif, statut) 
                VALUES (:nom_patient, :prenom_patient, :telephone, :email, 
                :code_medecin, :date_rdv, :heure_rdv, :motif, :statut)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($data);
    }

    public function getAll() {
        $sql = "SELECT r.*, m.nom as medecin_nom, m.prenom as medecin_prenom, 
                m.specialisation
                FROM rendez_vous r
                JOIN medecin m ON r.code_medecin = m.code_medecin
                ORDER BY r.date_rdv DESC, r.heure_rdv DESC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }

    public function getById($id_rdv) {
        $sql = "SELECT r.*, m.nom as medecin_nom, m.prenom as medecin_prenom, 
                m.specialisation
                FROM rendez_vous r
                JOIN medecin m ON r.code_medecin = m.code_medecin
                WHERE r.id_rdv = :id_rdv";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id_rdv' => $id_rdv]);
        return $stmt->fetch();
    }

    public function getByMedecin($code_medecin, $date = null) {
        if ($date) {
            $sql = "SELECT * FROM rendez_vous 
                    WHERE code_medecin = :code_medecin 
                    AND date_rdv = :date
                    ORDER BY heure_rdv";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['code_medecin' => $code_medecin, 'date' => $date]);
        } else {
            $sql = "SELECT * FROM rendez_vous 
                    WHERE code_medecin = :code_medecin
                    ORDER BY date_rdv DESC, heure_rdv DESC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['code_medecin' => $code_medecin]);
        }
        return $stmt->fetchAll();
    }

    public function updateStatut($id_rdv, $statut) {
        $sql = "UPDATE rendez_vous SET statut = :statut WHERE id_rdv = :id_rdv";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['id_rdv' => $id_rdv, 'statut' => $statut]);
    }

    public function isSlotAvailable($code_medecin, $date, $heure) {
        $sql = "SELECT COUNT(*) as count FROM rendez_vous 
                WHERE code_medecin = :code_medecin 
                AND date_rdv = :date 
                AND heure_rdv = :heure
                AND statut != 'annule'";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'code_medecin' => $code_medecin,
            'date' => $date,
            'heure' => $heure
        ]);
        return $stmt->fetch()['count'] == 0;
    }

    public function count() {
        $sql = "SELECT COUNT(*) as total FROM rendez_vous";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetch()['total'];
    }

    public function countByStatut($statut) {
        $sql = "SELECT COUNT(*) as total FROM rendez_vous WHERE statut = :statut";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['statut' => $statut]);
        return $stmt->fetch()['total'];
    }
}
?>