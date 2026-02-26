<?php
/* Page de Déconnexion - LabGenius */

// === SESSION EN PREMIER ===
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// === DÉTRUIRE LA SESSION ===
$_SESSION = [];
session_destroy();

// === SUPPRIMER LE COOKIE DE SESSION ===
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// === REDIRECTION VERS LA PAGE DE CONNEXION ===
header('Location: login.php');
exit;
?>