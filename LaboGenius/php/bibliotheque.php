<?php
/* Page Bibliothèque - LabGenius */

// === SESSION EN PREMIER ===
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// === PROTECTION DE LA PAGE ===
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

// === CONFIGURATION ===
error_reporting(E_ALL);
ini_set('display_errors', 1);

// === INCLUSIONS ===
require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/functions.php';

// === INITIALISATION ===
$message = '';
$message_type = '';

try {
    $db = new Database();
    $sequences = $db->getToutesSequences() ?: [];
    $favoris = $db->getFavoris() ?: [];
} catch (Exception $e) {
    $sequences = [];
    $favoris = [];
    $error = $e->getMessage();
}

// === TRAITEMENT DES ACTIONS ===
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (isset($_POST['action'])) {
            switch($_POST['action']) {
                case 'favori':
                    $db->toggleFavori($_POST['sequence_id']);
                    $message = 'Favori mis à jour';
                    $message_type = 'success';
                    break;
                case 'supprimer':
                    $db->supprimerSequence($_POST['sequence_id']);
                    $message = 'Séquence supprimée';
                    $message_type = 'success';
                    break;
            }
        }
    } catch (Exception $e) {
        $message = "Erreur: " . $e->getMessage();
        $message_type = 'error';
    }
    
    // Recharger les données
    $sequences = $db->getToutesSequences();
    $favoris = $db->getFavoris();
}

$page_title = 'Bibliothèque';
$page_css = 'bibliotheque.css';
$page_js = 'bibliotheque.js';
include '../templates/header.php';
?>

<main class="bibliotheque-main">
    <h1 class="page-title">
        <i class="fas fa-book"></i>
        Bibliothèque Génomique
    </h1>
    
    <?php if (isset($error)): ?>
        <div class="message error">
            <i class="fas fa-exclamation-circle"></i>
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>
    
    <?php if ($message): ?>
        <div class="message <?= $message_type ?>">
            <i class="fas <?= $message_type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle' ?>"></i>
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <!-- Filtres -->
    <div class="filters-bar">
        <button class="filter-btn active" onclick="filtrer('all')">
            <i class="fas fa-list"></i> Toutes (<?= count($sequences) ?>)
        </button>
        <button class="filter-btn" onclick="filtrer('favoris')">
            <i class="fas fa-star"></i> Favoris (<?= count($favoris) ?>)
        </button>
    </div>

    <!-- Grille des séquences -->
    <div class="sequences-grid" id="sequences-grid">
        <?php if (empty($sequences)): ?>
            <div class="empty-state">
                <i class="fas fa-dna"></i>
                <p>Aucune séquence dans la bibliothèque</p>
                <p class="empty-state-sub">
                    <a href="sequenceur.php" class="btn-primary" style="display: inline-block; margin-top: 1rem;">
                        <i class="fas fa-plus"></i> Créer une séquence
                    </a>
                </p>
            </div>
        <?php else: ?>
            <?php foreach ($sequences as $seq): ?>
                <div class="sequence-card <?= $seq['favori'] ? 'favori' : '' ?>" 
                     data-id="<?= $seq['id'] ?>" 
                     data-favori="<?= $seq['favori'] ? 'true' : 'false' ?>">
                    
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-dna"></i>
                            <?= htmlspecialchars($seq['nom']) ?>
                        </h3>
                        <form method="POST" class="favori-form">
                            <input type="hidden" name="action" value="favori">
                            <input type="hidden" name="sequence_id" value="<?= $seq['id'] ?>">
                            <button type="submit" class="btn-favori" title="Favori">
                                <i class="fas <?= $seq['favori'] ? 'fa-star' : 'fa-star' ?>" 
                                   style="color: <?= $seq['favori'] ? 'var(--warning)' : 'var(--text-muted)' ?>"></i>
                            </button>
                        </form>
                    </div>
                    
                    <div class="card-body">
                        <div class="sequence-preview">
                            <?php 
                            $preview = substr($seq['sequence'], 0, 60);
                            for ($i = 0; $i < strlen($preview); $i++):
                                $base = $preview[$i];
                                $color = $base === 'A' ? '#ff3b6f' : ($base === 'T' ? '#00fff9' : ($base === 'G' ? '#00ff9d' : '#ffd700'));
                            ?>
                                <span class="base-mini" style="background: <?= $color ?>"><?= $base ?></span>
                            <?php endfor; ?>
                            <?php if (strlen($seq['sequence']) > 60): ?>
                                <span class="preview-more">...</span>
                            <?php endif; ?>
                        </div>
                        
                        <div class="card-meta">
                            <span title="Longueur">
                                <i class="fas fa-arrows-alt-h"></i> <?= strlen($seq['sequence']) ?> bases
                            </span>
                            <span title="Date de création">
                                <i class="far fa-calendar"></i> <?= date('d/m/Y', strtotime($seq['date_creation'])) ?>
                            </span>
                        </div>
                        
                        <?php if (!empty($seq['description'])): ?>
                            <div class="card-description">
                                <?= htmlspecialchars($seq['description']) ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="card-actions">
                            <a href="sequenceur.php?id=<?= $seq['id'] ?>" class="btn-action btn-edit">
                                <i class="fas fa-edit"></i> Ouvrir
                            </a>
                            <a href="synthese.php?sequence=<?= urlencode($seq['sequence']) ?>" class="btn-action btn-synthese">
                                <i class="fas fa-flask"></i> Synthèse
                            </a>
                            <form method="POST" class="delete-form" onsubmit="return confirm('Supprimer cette séquence ?')">
                                <input type="hidden" name="action" value="supprimer">
                                <input type="hidden" name="sequence_id" value="<?= $seq['id'] ?>">
                                <button type="submit" class="btn-delete" title="Supprimer">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</main>

<?php include '../templates/footer.php'; ?>