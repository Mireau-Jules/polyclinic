<?php
session_start();
require_once '../config/database.php';
require_once '../models/Patient.php';

$patientModel = new Patient($pdo);

// Recherche
$patients = [];
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $patients = $patientModel->search($_GET['search']);
} else {
    $patients = $patientModel->getAll();
}

$pageTitle = "Gestion des Patients";
include '../includes/header.php';
?>

<div class="page-header">
    <h2>Gestion des Patients</h2>
    <a href="add_patient.php" class="btn btn-primary">➕ Nouveau Patient</a>
</div>

<div class="search-box">
    <form method="GET" action="">
        <span class="search-icon">🔍</span>
        <input type="text" name="search" class="form-control" 
               placeholder="Rechercher par nom, prénom, téléphone ou n° dossier..." 
               value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
    </form>
</div>

<div class="content-card">
    <?php if (empty($patients)): ?>
        <p class="empty-state">Aucun patient trouvé</p>
    <?php else: ?>
        <table class="data-table">
            <thead>
                <tr>
                    <th>N° Dossier</th>
                    <th>Nom Complet</th>
                    <th>Sexe</th>
                    <th>Age</th>
                    <th>Téléphone</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($patients as $p): ?>
                <tr>
                    <td><strong>#<?php echo $p['no_dossier']; ?></strong></td>
                    <td><?php echo htmlspecialchars($p['prenom'] . ' ' . $p['nom']); ?></td>
                    <td><?php echo $p['sexe'] == 'M' ? '👨 Masculin' : '👩 Féminin'; ?></td>
                    <td><?php echo $p['age']; ?> ans</td>
                    <td><?php echo htmlspecialchars($p['telephone']); ?></td>
                    <td class="action-buttons">
                        <a href="patient_details.php?id=<?php echo $p['no_dossier']; ?>" 
                           class="btn btn-sm btn-primary">Voir</a>
                        <a href="edit_patient.php?id=<?php echo $p['no_dossier']; ?>" 
                           class="btn btn-sm btn-success">Modifier</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<style>
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.content-card {
    background: var(--white);
    padding: 2rem;
    border-radius: 0.75rem;
    box-shadow: var(--shadow);
}
</style>

<?php include '../includes/footer.php'; ?>