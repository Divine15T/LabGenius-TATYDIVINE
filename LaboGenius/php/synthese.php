<?php
/* Page Synthèse - LabGenius */

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
try {
    $db = new Database();
    $sequences = $db->getToutesSequences() ?: [];
} catch (Exception $e) {
    $sequences = [];
    $error = $e->getMessage();
}

// === TRAITEMENT ===
$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['sequence'])) {
        $_SESSION['synthese_en_cours'] = [
            'sequence' => strtoupper($_POST['sequence']),
            'nom' => $_POST['nom_experience'] ?? 'Synthèse',
            'date' => time()
        ];
        $message = "Séquence préparée";
        $message_type = 'success';
    }
}

$page_title = 'Synthèse';
$page_css = 'synthese.css';
$page_js = 'synthese.js';
include '../templates/header.php';
?>

<main class="synthese-main">
    <h1 class="page-title">
        <i class="fas fa-flask"></i>
        Machine de Synthèse
    </h1>
    
    <?php if ($message): ?>
        <div class="message <?= $message_type ?>">
            <i class="fas <?= $message_type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle' ?>"></i>
            <?= $message ?>
        </div>
    <?php endif; ?>

    <div class="synthese-grid">
        <!-- Formulaire de saisie -->
        <div class="card">
            <h2 class="card-title">
                <i class="fas fa-pen"></i>
                Saisie de la séquence
            </h2>
            
            <form method="POST" class="synthese-form">
                <div class="form-group">
                    <label>
                        <i class="fas fa-tag"></i>
                        Nom de l'expérience
                    </label>
                    <input type="text" name="nom_experience" class="form-input" 
                           value="<?= $_SESSION['synthese_en_cours']['nom'] ?? '' ?>" 
                           placeholder="Ex: Test GFP">
                </div>
                
                <div class="form-group">
                    <label>
                        <i class="fas fa-dna"></i>
                        Séquence ADN
                    </label>
                    <textarea name="sequence" rows="4" class="form-input sequence-textarea" 
                              placeholder="ATGC..."><?= $_SESSION['synthese_en_cours']['sequence'] ?? 'ATGC' ?></textarea>
                </div>
                
                <?php if (!empty($sequences)): ?>
                <div class="form-group">
                    <label>
                        <i class="fas fa-book"></i>
                        Charger depuis bibliothèque
                    </label>
                    <select id="bibliotheque" class="form-select" onchange="chargerSequence()">
                        <option value="">-- Sélectionner --</option>
                        <?php foreach ($sequences as $seq): ?>
                            <option value="<?= $seq['id'] ?>" 
                                    data-sequence="<?= $seq['sequence'] ?>"
                                    data-nom="<?= $seq['nom'] ?>">
                                <?= htmlspecialchars($seq['nom']) ?> (<?= strlen($seq['sequence']) ?> bases)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php endif; ?>
                
                <button type="submit" class="btn-primary btn-block">
                    <i class="fas fa-check"></i>
                    Préparer la synthèse
                </button>
            </form>
        </div>

        <!-- Simulation -->
        <div class="card">
            <h2 class="card-title">
                <i class="fas fa-microscope"></i>
                Simulation
            </h2>
            
            <?php if (isset($_SESSION['synthese_en_cours'])): ?>
                <!-- Aperçu de la séquence -->
                <div class="sequence-preview">
                    <div class="preview-label">
                        <i class="fas fa-dna"></i>
                        Séquence à synthétiser:
                    </div>
                    <div class="preview-sequence" id="preview-sequence">
                        <?= colorerSequence($_SESSION['synthese_en_cours']['sequence']) ?>
                    </div>
                </div>
                
                <!-- Barre de progression -->
                <div class="progress-section">
                    <div class="progress-header">
                        <span><i class="fas fa-chart-line"></i> Progression</span>
                        <span id="progress-text">0%</span>
                    </div>
                    <div class="progress-container">
                        <div id="progress-bar" class="progress-bar"></div>
                    </div>
                </div>
                
                <!-- Bouton de démarrage -->
                <button onclick="demarrerSynthese()" id="btn-demarrer" class="btn-primary btn-block">
                    <i class="fas fa-play"></i>
                    Démarrer la synthèse
                </button>
                
                <!-- Résultat -->
                <div id="resultat" class="resultat-container"></div>
                
            <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-dna"></i>
                    <p>Aucune séquence chargée</p>
                    <p class="empty-state-sub">Saisissez une séquence à synthétiser</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php include '../templates/footer.php'; ?>