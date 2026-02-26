<?php
/* Page Carnet de Laboratoire - LabGenius */

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
    $notes = $db->getToutesNotes() ?: [];
    $experiences = $db->getDernieresExperiences(20) ?: [];
    $sequences = $db->getToutesSequences() ?: [];
} catch (Exception $e) {
    $notes = [];
    $experiences = [];
    $sequences = [];
    $error = $e->getMessage();
}

// === TRAITEMENT DES ACTIONS ===
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (isset($_POST['action'])) {
            switch($_POST['action']) {
                case 'ajouter_note':
                    $db->ajouterNoteLaboratoire(
                        $_POST['titre'],
                        $_POST['contenu'],
                        $_POST['sequence_id'] ?? null,
                        $_POST['tags'] ?? '',
                        isset($_POST['important'])
                    );
                    $message = "Note ajoutée";
                    $message_type = 'success';
                    break;
                    
                case 'supprimer_note':
                    $db->supprimerNote($_POST['note_id']);
                    $message = "Note supprimée";
                    $message_type = 'success';
                    break;
            }
        }
    } catch (Exception $e) {
        $message = "Erreur: " . $e->getMessage();
        $message_type = 'error';
    }
    
    // Recharger les données
    $notes = $db->getToutesNotes();
}

$page_title = 'Carnet';
$page_css = 'carnet.css';
$page_js = 'carnet.js';
include '../templates/header.php';
?>

<main class="carnet-main">
    <div class="page-header">
        <h1 class="page-title">
            <i class="fas fa-notebook"></i>
            Carnet de Laboratoire
        </h1>
        <button onclick="ouvrirModal()" class="btn-primary">
            <i class="fas fa-plus"></i>
            Nouvelle note
        </button>
    </div>
    
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

    <div class="carnet-grid">
        <!-- Section Notes -->
        <section class="notes-section">
            <h2 class="section-title">
                <i class="fas fa-sticky-note"></i>
                Mes notes
            </h2>
            
            <div class="notes-grid">
                <?php if (empty($notes)): ?>
                    <div class="empty-state">
                        <i class="fas fa-pen"></i>
                        <p>Aucune note pour le moment</p>
                        <button onclick="ouvrirModal()" class="btn-primary" style="margin-top: 1rem;">
                            <i class="fas fa-plus"></i> Créer une note
                        </button>
                    </div>
                <?php else: ?>
                    <?php foreach ($notes as $note): ?>
                        <div class="note-card <?= $note['important'] ? 'important' : '' ?>">
                            <div class="note-header">
                                <h3 class="note-title">
                                    <?php if ($note['important']): ?>
                                        <i class="fas fa-star important-star"></i>
                                    <?php endif; ?>
                                    <?= htmlspecialchars($note['titre']) ?>
                                </h3>
                                <div class="note-actions">
                                    <form method="POST" onsubmit="return confirm('Supprimer cette note ?')">
                                        <input type="hidden" name="action" value="supprimer_note">
                                        <input type="hidden" name="note_id" value="<?= $note['id'] ?>">
                                        <button type="submit" class="btn-icon" title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            
                            <div class="note-date">
                                <i class="far fa-clock"></i> <?= date('d/m/Y H:i', strtotime($note['date_creation'])) ?>
                            </div>
                            
                            <div class="note-content">
                                <?= nl2br(htmlspecialchars($note['contenu'])) ?>
                            </div>
                            
                            <?php if (!empty($note['sequence_associee'])): ?>
                                <div class="note-sequence">
                                    <i class="fas fa-dna"></i>
                                    <span class="sequence-preview-small">
                                        <?= substr($note['sequence_associee'], 0, 30) ?>...
                                    </span>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($note['tags'])): ?>
                                <div class="note-tags">
                                    <?php foreach (explode(',', $note['tags']) as $tag): ?>
                                        <?php if (trim($tag)): ?>
                                            <span class="tag">
                                                <i class="fas fa-tag"></i> <?= trim($tag) ?>
                                            </span>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>

        <!-- Section Journal -->
        <section class="journal-section">
            <h2 class="section-title">
                <i class="fas fa-history"></i>
                Journal des expériences
            </h2>
            
            <div class="journal-list">
                <?php if (empty($experiences)): ?>
                    <div class="empty-state">
                        <i class="fas fa-flask"></i>
                        <p>Aucune expérience</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($experiences as $exp): ?>
                        <div class="journal-entry">
                            <span class="journal-icon" style="color: <?= $exp['succes'] ? 'var(--success)' : 'var(--error)' ?>">
                                <i class="fas <?= $exp['succes'] ? 'fa-check-circle' : 'fa-times-circle' ?>"></i>
                            </span>
                            <div class="journal-content">
                                <div class="journal-description"><?= htmlspecialchars($exp['description']) ?></div>
                                <div class="journal-time"><?= date('H:i', strtotime($exp['timestamp'])) ?></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>
    </div>
</main>

<!-- Modal d'ajout de note -->
<div id="noteModal" class="modal">
    <div class="modal-content">
        <span class="modal-close" onclick="fermerModal()">&times;</span>
        <h2 class="modal-title">
            <i class="fas fa-pen"></i> Nouvelle note
        </h2>
        
        <form method="POST" class="modal-form">
            <input type="hidden" name="action" value="ajouter_note">
            
            <div class="form-group">
                <label>
                    <i class="fas fa-heading"></i> Titre
                </label>
                <input type="text" name="titre" required class="form-input">
            </div>
            
            <div class="form-group">
                <label>
                    <i class="fas fa-align-left"></i> Contenu
                </label>
                <textarea name="contenu" rows="5" required class="form-input"></textarea>
            </div>
            
            <?php if (!empty($sequences)): ?>
            <div class="form-group">
                <label>
                    <i class="fas fa-dna"></i> Séquence associée
                </label>
                <select name="sequence_id" class="form-select">
                    <option value="">Aucune</option>
                    <?php foreach ($sequences as $seq): ?>
                        <option value="<?= $seq['id'] ?>"><?= htmlspecialchars($seq['nom']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <?php endif; ?>
            
            <div class="form-group">
                <label>
                    <i class="fas fa-tags"></i> Tags
                </label>
                <input type="text" name="tags" placeholder="pcr, mutation, gfp" class="form-input">
            </div>
            
            <div class="form-group checkbox">
                <label>
                    <input type="checkbox" name="important" value="1">
                    <i class="far fa-star"></i> Important
                </label>
            </div>
            
            <button type="submit" class="btn-primary btn-block">
                <i class="fas fa-save"></i> Enregistrer
            </button>
        </form>
    </div>
</div>

<?php include '../templates/footer.php'; ?>