<?php
require_once '../config/database.php';
require_once '../models/Patient.php';

class PatientController {
    private $model;
    
    public function __construct($pdo) {
        $this->model = new Patient($pdo);
    }
    
    public function delete($no_dossier) {
        session_start();
        if ($this->model->delete($no_dossier)) {
            $_SESSION['success'] = "Patient supprimé avec succès.";
        } else {
            $_SESSION['error'] = "Erreur lors de la suppression.";
        }
        header('Location: ../views/patients.php');
        exit;
    }
    
    public function update($no_dossier, $data) {
        session_start();
        if ($this->model->update($no_dossier, $data)) {
            $_SESSION['success'] = "Patient modifié avec succès.";
            header('Location: ../views/patients.php');
        } else {
            $_SESSION['error'] = "Erreur lors de la modification.";
            header('Location: ../views/edit_patient.php?id=' . $no_dossier);
        }
        exit;
    }
}

// Gestion des actions
if (isset($_GET['action'])) {
    $controller = new PatientController($pdo);
    
    if ($_GET['action'] == 'delete' && isset($_GET['id'])) {
        $controller->delete($_GET['id']);
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    $controller = new PatientController($pdo);
    $data = [
        'nom' => trim($_POST['nom']),
        'prenom' => trim($_POST['prenom']),
        'sexe' => $_POST['sexe'],
        'telephone' => trim($_POST['telephone']),
        'adresse' => trim($_POST['adresse']),
        'age' => intval($_POST['age'])
    ];
    $controller->update($_POST['no_dossier'], $data);
}
?>