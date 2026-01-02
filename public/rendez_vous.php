<?php
session_start();
require_once '../config/database.php';
require_once '../models/Medecin.php';
require_once '../models/RendezVous.php';

$medecinModel = new Medecin($pdo);
$rdvModel = new RendezVous($pdo);

$medecins = $medecinModel->getAll();
$success = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = [
        'nom_patient' => trim($_POST['nom']),
        'prenom_patient' => trim($_POST['prenom']),
        'telephone' => trim($_POST['telephone']),
        'email' => trim($_POST['email']),
        'code_medecin' => $_POST['code_medecin'],
        'date_rdv' => $_POST['date_rdv'],
        'heure_rdv' => $_POST['heure_rdv'],
        'motif' => trim($_POST['motif']),
        'statut' => 'en_attente'
    ];
    
    // Vérifier disponibilité
    if ($rdvModel->isSlotAvailable($data['code_medecin'], $data['date_rdv'], $data['heure_rdv'])) {
        if ($rdvModel->create($data)) {
            $success = true;
        } else {
            $_SESSION['error'] = "Erreur lors de la prise de rendez-vous";
        }
    } else {
        $_SESSION['error'] = "Ce créneau n'est plus disponible";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prendre un Rendez-vous - Polyclinique</title>
    <link rel="stylesheet" href="/public/css/style.css">
    <style>
        body {
            background: var(--light);
        }
        
        .rdv-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 3rem 2rem;
            text-align: center;
        }
        
        .rdv-container {
            max-width: 800px;
            margin: -2rem auto 3rem;
            background: white;
            padding: 2rem;
            border-radius: 1rem;
            box-shadow: var(--shadow-lg);
        }
        
        .success-message {
            text-align: center;
            padding: 3rem;
        }
        
        .success-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div class="rdv-header">
        <h1>📅 Prendre un Rendez-vous</h1>
        <p>Remplissez le formulaire ci-dessous</p>
    </div>
    
    <div class="rdv-container">
        <?php if ($success): ?>
            <div class="success-message">
                <div class="success-icon">✅</div>
                <h2>Rendez-vous confirmé !</h2>
                <p>Nous vous contacterons bientôt pour confirmer votre rendez-vous.</p>
                <p>Un email de confirmation a été envoyé.</p>
                <div style="margin-top: 2rem;">
                    <a href="/public_home.php" class="btn btn-primary">Retour à l'accueil</a>
                    <a href="/public/rendez_vous.php" class="btn">Nouveau rendez-vous</a>
                </div>
            </div>
        <?php else: ?>
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <h3>Vos informations</h3>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="nom">Nom *</label>
                        <input type="text" id="nom" name="nom" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="prenom">Prénom *</label>
                        <input type="text" id="prenom" name="prenom" class="form-control" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="telephone">Téléphone *</label>
                        <input type="tel" id="telephone" name="telephone" class="form-control" 
                               placeholder="+509 0000-0000" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" class="form-control" 
                               placeholder="votre@email.com">
                    </div>
                </div>
                
                <h3 style="margin-top: 2rem;">Détails du rendez-vous</h3>
                
                <div class="form-group">
                    <label for="code_medecin">Médecin *</label>
                    <select id="code_medecin" name="code_medecin" class="form-control" required>
                        <option value="">Sélectionner un médecin...</option>
                        <?php foreach ($medecins as $m): ?>
                        <option value="<?php echo $m['code_medecin']; ?>">
                            Dr. <?php echo htmlspecialchars($m['prenom'] . ' ' . $m['nom']); ?> 
                            - <?php echo htmlspecialchars($m['specialisation']); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="date_rdv">Date *</label>
                        <input type="date" id="date_rdv" name="date_rdv" class="form-control" 
                               min="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="heure_rdv">Heure *</label>
                        <select id="heure_rdv" name="heure_rdv" class="form-control" required>
                            <option value="">Sélectionner une heure...</option>
                            <option value="08:00">08:00</option>
                            <option value="09:00">09:00</option>
                            <option value="10:00">10:00</option>
                            <option value="11:00">11:00</option>
                            <option value="14:00">14:00</option>
                            <option value="15:00">15:00</option>
                            <option value="16:00">16:00</option>
                            <option value="17:00">17:00</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="motif">Motif de consultation *</label>
                    <textarea id="motif" name="motif" class="form-control" rows="4" 
                              placeholder="Décrivez brièvement la raison de votre consultation..." required></textarea>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Confirmer le rendez-vous</button>
                    <a href="/public_home.php" class="btn">Annuler</a>
                </div>
            </form>
        <?php endif; ?>
    </div>
    
    <style>
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }
        
        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</body>
</html>