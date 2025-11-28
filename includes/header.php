<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle . ' - ' : ''; ?>Polyclinique</title>
    <link rel="stylesheet" href="/public/css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <div class="nav-brand">
                <h1>🏥 Polyclinique</h1>
            </div>
            <ul class="nav-menu">
                <li><a href="/index.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">Tableau de bord</a></li>
                <li><a href="/views/patients.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'patients.php' ? 'active' : ''; ?>">Patients</a></li>
                <li><a href="/views/consultations.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'consultations.php' ? 'active' : ''; ?>">Consultations</a></li>
                <li><a href="/views/medecins.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'medecins.php' ? 'active' : ''; ?>">Médecins</a></li>
            </ul>
        </div>
    </nav>
    <main class="container"><?php
        if (isset($_SESSION['success'])) {
            echo '<div class="alert success">' . $_SESSION['success'] . '</div>';
            unset($_SESSION['success']);
        }
        if (isset($_SESSION['error'])) {
            echo '<div class="alert error">' . $_SESSION['error'] . '</div>';
            unset($_SESSION['error']);
        }
        ?>