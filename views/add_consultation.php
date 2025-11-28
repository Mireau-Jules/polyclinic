<?php
session_start();
require_once '../config/database.php';
require_once '../models/Patient.php';
require_once '../models/Medecin.php';
require_once '../models/Consultation.php';
require_once '../models/Prescription.php';

$patientModel = new Patient($pdo);
$medecinModel = new Medecin($pdo);
$consultationModel = new Consultation($pdo);
$prescriptionModel = new Prescription($pdo);

$patients = $patientModel->getAll();
$medecins = $medecinModel->getAll();

$selectedPatient = isset($_GET['patient_id']) ? $_GET['patient_id'] : '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Créer la consultation
    $consultationData = [
        'no_dossier' => $_POST['no_dossier'],
        'code_medecin' => $_POST['code_medecin'],
        'symptome' => trim($_POST['symptome']),
        'date_consultation' => $_POST['date_consultation']
    ];
    
    if ($consultationModel->create($consultationData)) {
        $lastConsultationId = $consultationModel->getLastInsertId();
        
        // Ajouter prescription si fournie
        if (!empty($_POST['ordonnance'])) {
            $prescriptionData = [
                'id_consultation' => $lastConsultationId,
                'ordonnance' => trim($_POST['ordonnance'])
            ];
            $prescriptionModel->create($prescriptionData);
        }
        
        $_SESSION['success'] = "Consultation enregistrée avec succès !";
        header('Location: patient_details.php?id=' . $_POST['no_dossier']);
        exit;
    } else {
        $_SESSION['error'] = "Erreur lors de l'enregistrement.";
    }
}

$pageTitle = "Nouvelle Consultation";
include '../includes/header.php';
?>

<div class="page-header">
    <h2>Nouvelle Consultation</h2>
    <a href="consultations.php" class="btn btn-sm">← Retour</a>
</div>

<div class="form-card">
    <form method="POST" action="">
        <div class="form-group">
            <label for="no_dossier">Patient *</label>
            <select id="no_dossier" name="no_dossier" class="form-control" required>
                <option value="">Sélectionner un patient...</option>
                <?php foreach ($patients as $p): ?>
                <option value="<?php echo $p['no_dossier']; ?>" 
                        <?php echo $selectedPatient == $p['no_dossier'] ? 'selected' : ''; ?>>
                    #<?php echo $p['no_dossier']; ?> - <?php echo htmlspecialchars($p['prenom'] . ' ' . $p['nom']); ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="form-row">
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
            
            <div class="form-group">
                <label for="date_consultation">Date de consultation *</label>
                <input type="date" id="date_consultation" name="date_consultation" 
                       class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
            </div>
        </div>
        
        <div class="form-group">
            <label for="symptome">Symptômes / Motif de consultation *</label>
            <textarea id="symptome" name="symptome" class="form-control" 
                      rows="4" required placeholder="Décrivez les symptômes du patient..."></textarea>
        </div>
        
        <div class="form-group">
            <label for="ordonnance">Ordonnance / Prescription (optionnel)</label>
            <textarea id="ordonnance" name="ordonnance" class="form-control" 
                      rows="4" placeholder="Médicaments prescrits, posologie, instructions..."></textarea>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Enregistrer la consultation</button>
            <a href="consultations.php" class="btn">Annuler</a>
        </div>
    </form>
</div>

<style>
.form-card {
    background: var(--white);
    padding: 2rem;
    border-radius: 0.75rem;
    box-shadow: var(--shadow);
    max-width: 900px;
}

.form-row {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 1.5rem;
}

@media (max-width: 768px) {
    .form-row {
        grid-template-columns: 1fr;
    }
}
</style>

<?php include '../includes/footer.php'; ?>