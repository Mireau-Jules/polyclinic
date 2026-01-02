<?php
session_start();
require_once '../config/database.php';
require_once '../config/auth.php';

// Si déjà connecté, rediriger vers le dashboard
if (isLoggedIn()) {
    header('Location: /index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    // Rechercher le médecin
    $sql = "SELECT * FROM medecin WHERE email = :email AND is_active = 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['email' => $email]);
    $medecin = $stmt->fetch();
    
    if ($medecin && verifyPassword($password, $medecin['password'])) {
        // Connexion réussie
        login($medecin);
        
        // Rediriger vers la page demandée ou le dashboard
        $redirect = $_SESSION['redirect_after_login'] ?? '/index.php';
        unset($_SESSION['redirect_after_login']);
        header('Location: ' . $redirect);
        exit;
    } else {
        $error = "Email ou mot de passe incorrect";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Polyclinique</title>
    <link rel="stylesheet" href="/public/css/style.css">
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .login-container {
            background: white;
            padding: 3rem;
            border-radius: 1rem;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            width: 100%;
            max-width: 400px;
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .login-header h1 {
            color: var(--primary);
            margin-bottom: 0.5rem;
        }
        
        .login-header p {
            color: var(--gray);
        }
        
        .back-home {
            text-align: center;
            margin-top: 1.5rem;
        }
        
        .back-home a {
            color: var(--primary);
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h1>🏥 Polyclinique</h1>
            <p>Connexion Médecin</p>
        </div>
        
        <?php if ($error): ?>
            <div class="alert error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" class="form-control" 
                       placeholder="votre@email.com" required autofocus>
            </div>
            
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" class="form-control" 
                       placeholder="••••••••" required>
            </div>
            
            <button type="submit" class="btn btn-primary" style="width: 100%;">
                Se connecter
            </button>
        </form>
        
        <div class="back-home">
            <a href="/public_home.php">← Retour à l'accueil</a>
        </div>
        
        <div style="margin-top: 2rem; padding: 1rem; background: #f3f4f6; border-radius: 0.5rem; font-size: 0.875rem;">
            <strong>Compte de test :</strong><br>
            Email: admin@polyclinique.ht<br>
            Mot de passe: admin123
        </div>
    </div>
</body>
</html>