<?php
session_start();
require_once '../config/database.php';
require_once '../models/Medecin.php';

$medecinModel = new Medecin($pdo);
$medecins = $medecinModel->getAll();

$pageTitle = "Médecins";
include '../includes/header.php';
?>

<div class="page-header">
    <h2>Liste des Médecins</h2>
</div>

<div class="medecins-grid">
    <?php if (empty($medecins)): ?>
        <p class="empty-state">Aucun médecin enregistré</p>
    <?php else: ?>
        <?php foreach ($medecins as $m): ?>
        <div class="medecin-card">
            <div class="medecin-avatar">
                <?php echo $m['sexe'] == 'M' ? '👨‍⚕️' : '👩‍⚕️'; ?>
            </div>
            <div class="medecin-info">
                <h3>Dr. <?php echo htmlspecialchars($m['prenom'] . ' ' . $m['nom']); ?></h3>
                <p class="specialisation">📋 <?php echo htmlspecialchars($m['specialisation']); ?></p>
                <div class="medecin-contact">
                    <p>📞 <?php echo htmlspecialchars($m['telephone']); ?></p>
                    <p>📧 <?php echo htmlspecialchars($m['email']); ?></p>
                    <p>📍 <?php echo htmlspecialchars($m['adresse']); ?></p>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<style>
.page-header {
    margin-bottom: 2rem;
}

.medecins-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 1.5rem;
}

.medecin-card {
    background: var(--white);
    border-radius: 0.75rem;
    box-shadow: var(--shadow);
    padding: 2rem;
    transition: transform 0.3s, box-shadow 0.3s;
}

.medecin-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-lg);
}

.medecin-avatar {
    font-size: 4rem;
    text-align: center;
    margin-bottom: 1rem;
}

.medecin-info h3 {
    color: var(--primary);
    margin-bottom: 0.5rem;
    font-size: 1.3rem;
}

.specialisation {
    color: var(--gray);
    font-weight: 500;
    margin-bottom: 1rem;
    padding: 0.5rem 1rem;
    background: var(--light);
    border-radius: 0.5rem;
    display: inline-block;
}

.medecin-contact {
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid var(--light);
}

.medecin-contact p {
    margin: 0.5rem 0;
    color: var(--gray);
    font-size: 0.9rem;
}

@media (max-width: 768px) {
    .medecins-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<?php include '../includes/footer.php'; ?>