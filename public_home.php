<?php
session_start();
require_once 'config/database.php';

// Compter les médecins disponibles
$sql = "SELECT COUNT(*) as total FROM medecin WHERE is_active = 1";
$stmt = $pdo->query($sql);
$totalMedecins = $stmt->fetch()['total'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenue - Polyclinique</title>
    <link rel="stylesheet" href="/public/css/style.css">
    <style>
        .hero {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 4rem 2rem;
            text-align: center;
        }
        
        .hero h1 {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
        
        .hero p {
            font-size: 1.25rem;
            margin-bottom: 2rem;
            opacity: 0.9;
        }
        
        .hero-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .hero-buttons .btn {
            padding: 1rem 2rem;
            font-size: 1.1rem;
        }
        
        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            padding: 3rem 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .feature-card {
            background: white;
            padding: 2rem;
            border-radius: 1rem;
            box-shadow: var(--shadow);
            text-align: center;
            transition: transform 0.3s;
        }
        
        .feature-card:hover {
            transform: translateY(-5px);
        }
        
        .feature-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
        
        .feature-card h3 {
            color: var(--primary);
            margin-bottom: 0.5rem;
        }
        
        .btn-white {
            background: white;
            color: var(--primary);
        }
        
        .btn-white:hover {
            background: #f3f4f6;
        }
    </style>
</head>
<body>
    <div class="hero">
        <h1>🏥 Bienvenue à la Polyclinique</h1>
        <p>Votre santé est notre priorité</p>
        <div class="hero-buttons">
            <a href="/public/rendez_vous.php" class="btn btn-white">📅 Prendre un rendez-vous</a>
            <a href="/auth/login.php" class="btn btn-primary">👨‍⚕️ Espace Médecin</a>
        </div>
    </div>
    
    <div class="features">
        <div class="feature-card">
            <div class="feature-icon">👨‍⚕️</div>
            <h3><?php echo $totalMedecins; ?> Médecins Qualifiés</h3>
            <p>Une équipe de professionnels dévoués à votre service</p>
        </div>
        
        <div class="feature-card">
            <div class="feature-icon">📅</div>
            <h3>Rendez-vous en Ligne</h3>
            <p>Prenez rendez-vous facilement depuis chez vous</p>
        </div>
        
        <div class="feature-card">
            <div class="feature-icon">📋</div>
            <h3>Suivi Médical</h3>
            <p>Historique complet de vos consultations et prescriptions</p>
        </div>
        
        <div class="feature-card">
            <div class="feature-icon">⏰</div>
            <h3>Horaires Flexibles</h3>
            <p>Ouvert du lundi au samedi de 8h à 18h</p>
        </div>
        
        <div class="feature-card">
            <div class="feature-icon">💊</div>
            <h3>Prescriptions Digitales</h3>
            <p>Accès rapide à vos ordonnances médicales</p>
        </div>
        
        <div class="feature-card">
            <div class="feature-icon">🔒</div>
            <h3>Données Sécurisées</h3>
            <p>Protection totale de vos informations médicales</p>
        </div>
    </div>
    
    <footer class="footer">
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> Polyclinique - Tous droits réservés</p>
            <p>📞 +509 0000-0000 | 📧 contact@polyclinique.ht</p>
        </div>
    </footer>
</body>
</html>