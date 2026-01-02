<?php
// Système d'authentification

// Démarrer la session si pas déjà démarrée
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérifier si l'utilisateur est connecté
function isLoggedIn() {
    return isset($_SESSION['medecin_id']) && !empty($_SESSION['medecin_id']);
}

// Récupérer les informations du médecin connecté
function getCurrentMedecin() {
    if (isLoggedIn()) {
        return [
            'id' => $_SESSION['medecin_id'],
            'nom' => $_SESSION['medecin_nom'] ?? '',
            'prenom' => $_SESSION['medecin_prenom'] ?? '',
            'email' => $_SESSION['medecin_email'] ?? '',
            'specialisation' => $_SESSION['medecin_specialisation'] ?? ''
        ];
    }
    return null;
}

// Connecter un médecin
function login($medecin) {
    $_SESSION['medecin_id'] = $medecin['code_medecin'];
    $_SESSION['medecin_nom'] = $medecin['nom'];
    $_SESSION['medecin_prenom'] = $medecin['prenom'];
    $_SESSION['medecin_email'] = $medecin['email'];
    $_SESSION['medecin_specialisation'] = $medecin['specialisation'];
    $_SESSION['login_time'] = time();
}

// Déconnecter
function logout() {
    session_unset();
    session_destroy();
}

// Protéger une page (rediriger si pas connecté)
function requireLogin() {
    if (!isLoggedIn()) {
        $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
        header('Location: /auth/login.php');
        exit;
    }
}

// Vérifier le mot de passe
function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

// Hasher un mot de passe
function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}
?>