<?php
session_start();
require_once '../config/database.php';
require_once '../models/Consultation.php';

$consultationModel = new Consultation($pdo);
$consultations = $consultationModel->getAll();

$pageTitle = "Consultations";
include '../includes/header.php';
?>

<div class="page-header">
    <h2>Consultations</h2>
    <a href="add_consultation.php" class="btn btn-primary">➕ Nouvelle Consultation</a>
</div>

<div class="content-card">
    <?php if (empty($consultations)): ?>
        <p class="empty-state">Aucune consultation enregistrée</p>
    <?php else: ?>
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Date</th>
                    <th>Patient</th>
                    <th>Médecin</th>
                    <th>Spécialisation</th>
                    <th>Symptôme</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($consultations as $c): ?>
                <tr>
                    <td><strong>#<?php echo $c['id_consultation']; ?></strong></td>
                    <td><?php echo date('d/m/Y', strtotime($c['date_consultation'])); ?></td>
                    <td>
                        <a href="patient_details.php?id=<?php echo $c['no_dossier']; ?>">
                            <?php echo htmlspecialchars($c['patient_prenom'] . ' ' . $c['patient_nom']); ?>
                        </a>
                    </td>
                    <td>Dr. <?php echo htmlspecialchars($c['medecin_prenom'] . ' ' . $c['medecin_nom']); ?></td>
                    <td><em><?php echo htmlspecialchars($c['specialisation']); ?></em></td>
                    <td><?php echo htmlspecialchars(substr($c['symptome'], 0, 60)) . '...'; ?></td>
                    <td class="action-buttons">
                        <a href="patient_details.php?id=<?php echo $c['no_dossier']; ?>" 
                           class="btn btn-sm btn-primary">Voir détails</a>
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