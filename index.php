<?php
require_once 'config/database.php';
require_once 'includes/header.php';
?>

<main class="container">
    <h1>Bienvenue - Polyclinique</h1>
    <p>Système de Gestion des Patients</p>
    
    <nav class="main-menu">
        <a href="views/patients/list.php">Gestion des Patients</a>
        <a href="views/medecins/list.php">Gestion des Médecins</a>
        <a href="views/consultations/list.php">Consultations</a>
        <a href="views/prescriptions/list.php">Prescriptions</a>
    </nav>
</main>

<?php require_once 'includes/footer.php'; ?>
