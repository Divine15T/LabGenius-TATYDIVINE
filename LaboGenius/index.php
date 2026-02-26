<?php
/* Page d'accueil / Tableau de bord - LabGenius*/

// === SESSION EN PREMIER ===
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// === GESTION DU THÈME ===
if (isset($_GET['toggle_theme'])) {
    $_SESSION['theme'] = ($_SESSION['theme'] ?? 'dark') === 'dark' ? 'light' : 'dark';
    header('Location: ' . str_replace('?toggle_theme=1', '', $_SERVER['REQUEST_URI']));
    exit;
}

// === CONFIGURATION ===
error_reporting(E_ALL);
ini_set('display_errors', 1);

// === CONSTANTES ===
$BASE_COLORS = [
    'A' => '#ff3b6f',
    'T' => '#00fff9',
    'G' => '#00ff9d',
    'C' => '#ffd700'
];

// ===  INCLUSIONS ===
$base_path = __DIR__ . '/';
require_once __DIR__ . '/php/Database.php';
require_once $base_path . 'php/functions.php';

// === VÉRIFICATION ===
if (!class_exists('Database')) {
    die("Erreur: Configuration incomplète");
}

// === RÉCUPÉRATION DES DONNÉES ===
try {
    $db = new Database();
    $projets_en_cours = $db->getProjetsRecents(3) ?: [];
    $dernieres_sequences = $db->getDernieresSequences(5) ?: [];
    $statistiques = $db->getStatistiques() ?: [
        'total_sequences' => 0,
        'syntheses_reussies' => 0,
        'projets_actifs' => 0
    ];
    $logs_recents = $db->getLogsRecents(10) ?: [];
} catch (Exception $e) {
    $projets_en_cours = [];
    $dernieres_sequences = [];
    $statistiques = ['total_sequences' => 0, 'syntheses_reussies' => 0, 'projets_actifs' => 0];
    $logs_recents = [];
}

// === FONCTION DE VISUALISATION ===
function visualisationADN($longueur = 30) {
    global $BASE_COLORS;
    $bases = ['A', 'T', 'G', 'C'];
    $sequence = '';
    for ($i = 0; $i < $longueur; $i++) {
        $sequence .= $bases[array_rand($bases)];
    }
    
    $html = '<div class="adn-visualisation">';
    $html .= '<h3>Séquence d\'ADN</h3>';
    $html .= '<div class="adn-helice">';
    
    for ($i = 0; $i < strlen($sequence); $i+=2) {
        $base1 = $sequence[$i];
        $base2 = $sequence[$i+1] ?? 'A';
        
        $html .= "<div class='paire-bases'>";
        $html .= "<span class='base base-" . strtolower($base1) . "'>$base1</span>";
        $html .= "<span class='lien'></span>";
        $html .= "<span class='base base-" . strtolower($base2) . "'>$base2</span>";
        $html .= "</div>";
    }
    
    $html .= '</div>';
    $html .= '<div class="adn-legend">';
    $html .= '<span><span class="couleur base-a"></span> Adénine (A)</span>';
    $html .= '<span><span class="couleur base-t"></span> Thymine (T)</span>';
    $html .= '<span><span class="couleur base-g"></span> Guanine (G)</span>';
    $html .= '<span><span class="couleur base-c"></span> Cytosine (C)</span>';
    $html .= '</div>';
    $html .= '</div>';
    
    return $html;
}

$page_title = 'Tableau de bord';
$page_css = 'dashboard.css';
$page_js = 'dashboard.js'; 
include 'templates/header.php';
?>

<!-- Bloqueur mobile -->
<div id="mobile-blocker">
    <div class="blocker-content">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <rect x="2" y="3" width="20" height="18" rx="2" ry="2"></rect>
            <line x1="8" y1="9" x2="16" y2="9"></line>
            <line x1="8" y1="13" x2="16" y2="13"></line>
            <line x1="8" y1="17" x2="12" y2="17"></line>
        </svg>
        <h1>Interface laboratoire</h1>
        <p>Cette interface est optimisée pour les écrans de bureau.<br>Veuillez utiliser un écran plus large pour accéder au tableau de bord.</p>
        <span class="resolution-badge">Minimum 768px requis</span>
    </div>
</div>

<main class="dashboard-main">
    <h1 class="dashboard-title">
        <i class="fas fa-chart-pie"></i>
        Tableau de bord du laboratoire
    </h1>

    <!-- Visualisation ADN -->
    <section class="visualisation-section">
        <?= visualisationADN(10) ?>
    </section>

    <!-- Les indicateurs -->
    <section class="stats-cards">
        <div class="stat-card">
            <span class="stat-value"><?= (int)($statistiques['total_sequences'] ?? 0) ?></span>
            <span class="stat-label">Séquences stockées</span>
        </div>
        <div class="stat-card">
            <span class="stat-value"><?= (int)($statistiques['syntheses_reussies'] ?? 0) ?></span>
            <span class="stat-label">Synthèses réussies</span>
        </div>
        <div class="stat-card">
            <span class="stat-value"><?= (int)($statistiques['projets_actifs'] ?? 0) ?></span>
            <span class="stat-label">Projets en cours</span>
        </div>
    </section>

    <!-- La grille principale -->
    <div class="main-grid">
        <!-- Les Projets -->
        <section class="card">
            <h2 class="card-title">
                <i class="fas fa-project-diagram"></i>
                Projets en cours
            </h2>
            <div class="project-list">
                <?php if (empty($projets_en_cours)): ?>
                    <div class="empty-state">
                        <p>Aucun projet en cours</p>
                    </div>
                <?php else: ?>
                    <?php foreach($projets_en_cours as $projet): ?>
                        <div class="project-item">
                            <h3 class="project-name"><?= htmlspecialchars($projet['nom'] ?? 'Projet') ?></h3>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: <?= min(100, max(0, (int)($projet['avancement'] ?? 0))) ?>%;"></div>
                            </div>
                            <span class="project-status"><?= htmlspecialchars($projet['statut'] ?? 'En cours') ?></span>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>

        <!-- Dernières synthèses-->
        <section class="card fixed-height">
            <h2 class="card-title">
                <i class="fas fa-flask"></i>
                Dernières synthèses
            </h2>
            <div class="sequence-log scrollable">
                <?php if (empty($dernieres_sequences)): ?>
                    <div class="empty-state">
                        <p>Aucune synthèse récente</p>
                    </div>
                <?php else: ?>
                    <?php foreach($dernieres_sequences as $seq): ?>
                        <div class="log-entry">
                            <span class="log-time"><?= htmlspecialchars($seq['date'] ?? date('H:i')) ?></span>
                            <span class="log-sequence">
                                <?php 
                                if (function_exists('colorerSequence')) {
                                    echo colorerSequence($seq['sequence'] ?? 'ATGC');
                                } else {
                                    echo htmlspecialchars(substr($seq['sequence'] ?? 'ATGC', 0, 15));
                                }
                                ?>
                            </span>
                            <span class="log-status <?= ($seq['succes'] ?? false) ? 'success' : 'error' ?>">
                                <i class="fas <?= ($seq['succes'] ?? false) ? 'fa-check' : 'fa-times' ?>"></i>
                            </span>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>

        <!-- Journal -->
        <section class="card full-width">
            <h2 class="card-title">
                <i class="fas fa-history"></i>
                Journal de laboratoire
            </h2>
            <div class="lab-log">
                <?php if (empty($logs_recents)): ?>
                    <div class="empty-state">
                        <p>Journal vide</p>
                    </div>
                <?php else: ?>
                    <?php foreach($logs_recents as $log): ?>
                        <div class="log-entry">
                            <span class="log-timestamp">[<?= htmlspecialchars($log['timestamp'] ?? date('H:i:s')) ?>]</span>
                            <span class="log-description"><?= htmlspecialchars($log['description'] ?? '') ?></span>
                            <span class="log-status <?= ($log['succes'] ?? true) ? 'success' : 'error' ?>">
                                <i class="fas <?= ($log['succes'] ?? true) ? 'fa-check' : 'fa-times' ?>"></i>
                            </span>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>
    </div>

    <!-- Actions rapides -->
    <section class="actions-section">
        <h2 class="actions-title">
            <i class="fas fa-bolt"></i>
            Actions
        </h2>
        <div class="actions-grid">
            <a href="php/sequenceur.php" class="action-card">
                <i class="fas fa-edit action-icon"></i>
                <span>Séquenceur</span>
            </a>
            <a href="php/synthese.php" class="action-card">
                <i class="fas fa-flask action-icon"></i>
                <span>Synthèse</span>
            </a>
            <a href="php/bibliotheque.php" class="action-card">
                <i class="fas fa-book action-icon"></i>
                <span>Bibliothèque</span>
            </a>
        </div>
    </section>
</main>

<?php include 'templates/footer.php'; ?>