<?php
session_start();
require_once 'config/database.php';
require_once 'models/Patient.php';
require_once 'models/Consultation.php';
require_once 'models/Medecin.php';

$patientModel = new Patient($pdo);
$consultationModel = new Consultation($pdo);
$medecinModel = new Medecin($pdo);

// Statistiques
$totalPatients = $patientModel->count();
$totalConsultations = $consultationModel->count();
$totalMedecins = $medecinModel->count();

// Dernières consultations
$recentConsultations = $consultationModel->getAll();
$recentConsultations = array_slice($recentConsultations, 0, 5);

$pageTitle = "Tableau de bord";
include 'includes/header.php';
?>

<div class="dashboard">
    <h2>Tableau de bord</h2>
    
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">👥</div>
            <div class="stat-content">
                <h3><?php echo $totalPatients; ?></h3>
                <p>Patients</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">📋</div>
            <div class="stat-content">
                <h3><?php echo $totalConsultations; ?></h3>
                <p>Consultations</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">👨‍⚕️</div>
            <div class="stat-content">
                <h3><?php echo $totalMedecins; ?></h3>
                <p>Médecins</p>
            </div>
        </div>
    </div>

    <div class="recent-section">
        <h3>Dernières consultations</h3>
        <?php if (empty($recentConsultations)): ?>
            <p class="empty-state">Aucune consultation enregistrée</p>
        <?php else: ?>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Patient</th>
                        <th>Médecin</th>
                        <th>Symptôme</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentConsultations as $c): ?>
                    <tr>
                        <td><?php echo date('d/m/Y', strtotime($c['date_consultation'])); ?></td>
                        <td><?php echo htmlspecialchars($c['patient_prenom'] . ' ' . $c['patient_nom']); ?></td>
                        <td>Dr. <?php echo htmlspecialchars($c['medecin_prenom'] . ' ' . $c['medecin_nom']); ?></td>
                        <td><?php echo htmlspecialchars(substr($c['symptome'], 0, 50)) . '...'; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>