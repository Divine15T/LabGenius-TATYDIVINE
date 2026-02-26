<?php
/* Page Séquenceur - LabGenius */

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
require_once __DIR__ . '/Sequence.php';

// === INITIALISATION ===
try {
    $db = new Database();
    
    // Créer une séquence par défaut
    $sequence = new Sequence('ATGCGTACGTAGCTAGCTAGC');
    $sequence_brut = $sequence->getBases();
    $bases = str_split($sequence_brut);
    
    // Récupérer l'historique
    $historique = $_SESSION['historique_sequenceur'] ?? [];
    
} catch (Exception $e) {
    $error = $e->getMessage();
    $bases = str_split('ATGC');
    $historique = [];
}

$page_title = 'Séquenceur';
$page_css = 'sequenceur.css';
$page_js = 'sequenceur.js';
include '../templates/header.php';
?>

<main class="sequenceur-main">
    <h1 class="page-title">
        <i class="fas fa-dna"></i>
        Séquenceur de Génome
    </h1>

    <!-- Barre d'outils -->
    <div class="toolbar">
        <button class="btn-tool" onclick="ouvrirModalChargement()">
            <i class="fas fa-folder-open"></i>
            Charger
        </button>
        <button class="btn-tool" onclick="sauvegarder()">
            <i class="fas fa-save"></i>
            Sauvegarder
        </button>
        <button class="btn-tool" onclick="resetSequence()">
            <i class="fas fa-undo-alt"></i>
            Réinitialiser
        </button>
    </div>

    <!-- Options de vue -->
    <div class="view-options">
        <button class="view-option active" onclick="changerVue('texte')">
            <i class="fas fa-align-left"></i> Texte
        </button>
        <button class="view-option" onclick="changerVue('graphique')">
            <i class="fas fa-chart-bar"></i> Graphique
        </button>
        <button class="view-option" onclick="changerVue('lesdeux')">
            <i class="fas fa-columns"></i> Les deux
        </button>
    </div>

    <!-- Vue Texte -->
    <div id="vue-texte" class="sequence-texte">
        <?= $sequence_brut ?>
    </div>

    <!-- Vue Graphique -->
    <div id="vue-graphique" class="sequence-graphique">
        <div class="bases-graphiques" id="bases-graphiques">
            <?php foreach ($bases as $index => $base): ?>
                <div class="base-graphique base-<?= strtolower($base) ?>" 
                     data-position="<?= $index ?>"
                     data-base="<?= $base ?>"
                     title="Position <?= $index + 1 ?> - Cliquer pour muter"
                     onclick="muterPosition(<?= $index + 1 ?>)">
                    <span class="lettre"><?= $base ?></span>
                    <span class="position"><?= $index + 1 ?></span>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Barre d'outils d'édition -->
    <div class="editor-toolbar">
        <button class="btn-tool primary" onclick="muterAleatoire()">
            <i class="fas fa-dna"></i>
            Mutation aléatoire
        </button>
        <button class="btn-tool primary" onclick="ouvrirModalInsertion()">
            <i class="fas fa-plus-circle"></i>
            Insérer un gène
        </button>
    </div>

    <!-- Historique -->
    <div class="history-panel">
        <h2 class="panel-title">
            <i class="fas fa-history"></i>
            Historique des modifications
        </h2>
        <div id="history-list" class="history-list">
            <?php if (empty($historique)): ?>
                <div class="empty-state">
                    <i class="fas fa-clock"></i>
                    <p>Aucune modification</p>
                </div>
            <?php else: ?>
                <?php foreach ($historique as $entry): ?>
                    <div class="history-item">
                        <span class="history-time"><?= $entry['time'] ?></span>
                        <span class="history-action"><?= $entry['action'] ?></span>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</main>

<!-- Modal d'insertion -->
<div id="modal-insertion" class="modal">
    <div class="modal-content">
        <span class="modal-close" onclick="fermerModal()">&times;</span>
        <h3>Insérer un gène</h3>
        
        <div class="form-group">
            <label>Position d'insertion</label>
            <input type="number" id="position-insertion" min="0" value="0" class="form-input">
        </div>
        
        <div class="form-group">
            <label>Séquence du gène (A,T,G,C)</label>
            <input type="text" id="gene-sequence" placeholder="Ex: ATGC" class="form-input">
        </div>
        
        <button onclick="insererGene()" class="btn-tool primary" style="width:100%">
            Insérer
        </button>
    </div>
</div>

<?php include '../templates/footer.php'; ?>