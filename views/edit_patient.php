<?php
session_start();
require_once '../config/database.php';
require_once '../models/Patient.php';

if (!isset($_GET['id'])) {
    header('Location: patients.php');
    exit;
}

$patientModel = new Patient($pdo);
$patient = $patientModel->getById($_GET['id']);

if (!$patient) {
    $_SESSION['error'] = "Patient non trouvé.";
    header('Location: patients.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = [
        'nom' => trim($_POST['nom']),
        'prenom' => trim($_POST['prenom']),
        'sexe' => $_POST['sexe'],
        'telephone' => trim($_POST['telephone']),
        'adresse' => trim($_POST['adresse']),
        'age' => intval($_POST['age'])
    ];
    
    if ($patientModel->update($patient['no_dossier'], $data)) {
        $_SESSION['success'] = "Patient modifié avec succès !";
        header('Location: patient_details.php?id=' . $patient['no_dossier']);
        exit;
    } else {
        $_SESSION['error'] = "Erreur lors de la modification.";
    }
}

$pageTitle = "Modifier Patient";
include '../includes/header.php';
?>

<div class="page-header">
    <h2>Modifier Patient #<?php echo $patient['no_dossier']; ?></h2>
    <a href="patient_details.php?id=<?php echo $patient['no_dossier']; ?>" class="btn btn-sm">← Retour</a>
</div>

<div class="form-card">
    <form method="POST" action="">
        <div class="form-row">
            <div class="form-group">
                <label for="nom">Nom *</label>
                <input type="text" id="nom" name="nom" class="form-control" 
                       value="<?php echo htmlspecialchars($patient['nom']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="prenom">Prénom *</label>
                <input type="text" id="prenom" name="prenom" class="form-control" 
                       value="<?php echo htmlspecialchars($patient['prenom']); ?>" required>
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label for="sexe">Sexe *</label>
                <select id="sexe" name="sexe" class="form-control" required>
                    <option value="M" <?php echo $patient['sexe'] == 'M' ? 'selected' : ''; ?>>Masculin</option>
                    <option value="F" <?php echo $patient['sexe'] == 'F' ? 'selected' : ''; ?>>Féminin</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="age">Age *</label>
                <input type="number" id="age" name="age" class="form-control" 
                       value="<?php echo $patient['age']; ?>" min="0" max="150" required>
            </div>
        </div>
        
        <div class="form-group">
            <label for="telephone">Téléphone</label>
            <input type="tel" id="telephone" name="telephone" class="form-control" 
                   value="<?php echo htmlspecialchars($patient['telephone']); ?>">
        </div>
        
        <div class="form-group">
            <label for="adresse">Adresse</label>
            <textarea id="adresse" name="adresse" class="form-control" rows="3"><?php echo htmlspecialchars($patient['adresse']); ?></textarea>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn btn-success">Enregistrer les modifications</button>
            <a href="patient_details.php?id=<?php echo $patient['no_dossier']; ?>" class="btn">Annuler</a>
        </div>
    </form>
</div>

<style>
.form-card {
    background: var(--white);
    padding: 2rem;
    border-radius: 0.75rem;
    box-shadow: var(--shadow);
    max-width: 800px;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
}

.form-actions {
    display: flex;
    gap: 1rem;
    margin-top: 2rem;
}

@media (max-width: 768px) {
    .form-row {
        grid-template-columns: 1fr;
    }
}
</style>

<?php include '../includes/footer.php'; ?>