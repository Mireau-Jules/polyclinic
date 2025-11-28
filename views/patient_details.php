<?php
session_start();
require_once '../config/database.php';
require_once '../models/Patient.php';
require_once '../models/Consultation.php';
require_once '../models/Prescription.php';

if (!isset($_GET['id'])) {
    header('Location: patients.php');
    exit;
}

$patientModel = new Patient($pdo);
$consultationModel = new Consultation($pdo);
$prescriptionModel = new Prescription($pdo);

$patient = $patientModel->getById($_GET['id']);
if (!$patient) {
    $_SESSION['error'] = "Patient non trouvé.";
    header('Location: patients.php');
    exit;
}

$consultations = $consultationModel->getByPatient($patient['no_dossier']);

$pageTitle = "Dossier Patient";
include '../includes/header.php';
?>

<div class="page-header">
    <h2>Dossier Patient #<?php echo $patient['no_dossier']; ?></h2>
    <a href="patients.php" class="btn btn-sm">← Retour</a>
</div>

<div class="patient-profile">
    <div class="profile-card">
        <h3>Informations Personnelles</h3>
        <div class="info-grid">
            <div class="info-item">
                <span class="label">Nom complet:</span>
                <span class="value"><?php echo htmlspecialchars($patient['prenom'] . ' ' . $patient['nom']); ?></span>
            </div>
            <div class="info-item">
                <span class="label">Sexe:</span>
                <span class="value"><?php echo $patient['sexe'] == 'M' ? 'Masculin' : 'Féminin'; ?></span>
            </div>
            <div class="info-item">
                <span class="label">Age:</span>
                <span class="value"><?php echo $patient['age']; ?> ans</span>
            </div>
            <div class="info-item">
                <span class="label">Téléphone:</span>
                <span class="value"><?php echo htmlspecialchars($patient['telephone']); ?></span>
            </div>
            <div class="info-item full-width">
                <span class="label">Adresse:</span>
                <span class="value"><?php echo htmlspecialchars($patient['adresse']); ?></span>
            </div>
        </div>
        <div class="profile-actions">
            <a href="edit_patient.php?id=<?php echo $patient['no_dossier']; ?>" class="btn btn-success">Modifier</a>
            <a href="add_consultation.php?patient_id=<?php echo $patient['no_dossier']; ?>" class="btn btn-primary">Nouvelle Consultation</a>
        </div>
    </div>
</div>

<div class="consultations-section">
    <h3>Historique des Consultations (<?php echo count($consultations); ?>)</h3>
    
    <?php if (empty($consultations)): ?>
        <p class="empty-state">Aucune consultation enregistrée pour ce patient.</p>
    <?php else: ?>
        <?php foreach ($consultations as $c): 
            $prescriptions = $prescriptionModel->getByConsultation($c['id_consultation']);
        ?>
        <div class="consultation-card">
            <div class="consultation-header">
                <div>
                    <h4>📋 Consultation du <?php echo date('d/m/Y', strtotime($c['date_consultation'])); ?></h4>
                    <p>Dr. <?php echo htmlspecialchars($c['medecin_prenom'] . ' ' . $c['medecin_nom']); ?> 
                       - <em><?php echo htmlspecialchars($c['specialisation']); ?></em></p>
                </div>
            </div>
            
            <div class="consultation-body">
                <p><strong>Symptômes:</strong></p>
                <p><?php echo nl2br(htmlspecialchars($c['symptome'])); ?></p>
                
                <?php if (!empty($prescriptions)): ?>
                <div class="prescriptions">
                    <p><strong>💊 Prescriptions:</strong></p>
                    <?php foreach ($prescriptions as $p): ?>
                    <div class="prescription-item">
                        <?php echo nl2br(htmlspecialchars($p['ordonnance'])); ?>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<style>
.profile-card {
    background: var(--white);
    padding: 2rem;
    border-radius: 0.75rem;
    box-shadow: var(--shadow);
    margin-bottom: 2rem;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.5rem;
    margin: 1.5rem 0;
}

.info-item {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.info-item.full-width {
    grid-column: 1 / -1;
}

.info-item .label {
    font-weight: 600;
    color: var(--gray);
    font-size: 0.875rem;
}

.info-item .value {
    font-size: 1.1rem;
    color: var(--dark);
}

.profile-actions {
    display: flex;
    gap: 1rem;
    margin-top: 2rem;
    padding-top: 2rem;
    border-top: 1px solid var(--light);
}

.consultations-section {
    background: var(--white);
    padding: 2rem;
    border-radius: 0.75rem;
    box-shadow: var(--shadow);
}

.consultation-card {
    background: var(--light);
    border-radius: 0.5rem;
    padding: 1.5rem;
    margin-top: 1rem;
}

.consultation-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1rem;
}

.consultation-header h4 {
    color: var(--primary);
    margin-bottom: 0.5rem;
}

.consultation-body {
    background: var(--white);
    padding: 1rem;
    border-radius: 0.5rem;
}

.prescriptions {
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid var(--light);
}

.prescription-item {
    background: #fef3c7;
    padding: 1rem;
    border-radius: 0.5rem;
    margin-top: 0.5rem;
    border-left: 3px solid var(--warning);
}

@media (max-width: 768px) {
    .info-grid {
        grid-template-columns: 1fr;
    }
    
    .profile-actions {
        flex-direction: column;
    }
}
</style>

<?php include '../includes/footer.php'; ?>