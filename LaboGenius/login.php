<?php
/* Page de Connexion - LabGenius */

// === SESSION EN PREMIER ===
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// === REDIRECTION SI DÉJÀ CONNECTÉ ===
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

// === GESTION DU THÈME ===
if (isset($_GET['toggle_theme'])) {
    $_SESSION['theme'] = ($_SESSION['theme'] ?? 'dark') === 'dark' ? 'light' : 'dark';
    header('Location: login.php');
    exit;
}

// === TRAITEMENT ===
$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if ($username === 'labgenius' && $password === 'labgenius123') {
        $_SESSION['user_id'] = 1;
        $_SESSION['user_name'] = 'Chercheur';
        header('Location: index.php');
        exit;
    } else {
        $message = "Identifiants incorrects";
        $message_type = 'error';
    }
}

$page_title = 'Connexion';
$page_css = 'login.css';
$page_js = 'login.js';
include 'templates/header.php';
?>

<!-- CONTENU SPÉCIFIQUE À LOGIN -->
<div class="login-container">
    <div class="login-box">
        <div class="login-header">
            <div class="logo">
                <i class="fas fa-dna"></i>
                <h1>LabGenius</h1>
            </div>
            <p>Accès sécurisé au laboratoire</p>
        </div>
        
        <?php if ($message): ?>
            <div class="message <?= $message_type ?>">
                <i class="fas <?= $message_type === 'error' ? 'fa-exclamation-circle' : 'fa-check-circle' ?>"></i>
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" class="login-form">
            <div class="form-group">
                <label><i class="fas fa-user"></i> Identifiant</label>
                <div class="input-wrapper">
                    <i class="fas fa-user input-icon"></i>
                    <input type="text" name="username" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required autofocus>
                </div>
            </div>
            
            <div class="form-group">
                <label><i class="fas fa-lock"></i> Mot de passe</label>
                <div class="input-wrapper">
                    <i class="fas fa-lock input-icon"></i>
                    <input type="password" name="password" id="password" required>
                    <button type="button" class="toggle-password" onclick="togglePassword()">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>
            
            <button type="submit" class="btn-login">
                <i class="fas fa-sign-in-alt"></i> Se connecter
            </button>
        </form>
        
        <div class="login-footer">
            <p>Identifiants: <strong>labgenius / labgenius123</strong></p>
        </div>
    </div>
</div>

<?php include 'templates/footer.php'; ?>