<?php
// theme.php - Gestionnaire de thème

// Démarrer la session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Changer le thème (inverser)
$_SESSION['theme'] = ($_SESSION['theme'] ?? 'dark') === 'dark' ? 'light' : 'dark';

// Revenir à la page précédente
$redirect = $_SERVER['HTTP_REFERER'] ?? 'index.php';
header('Location: ' . $redirect);
exit;
?>