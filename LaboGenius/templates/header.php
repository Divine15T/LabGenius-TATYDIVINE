<?php
// templates/header.php

// === GESTION DES CHEMINS ===
$base_url = '';
$current_file = basename($_SERVER['PHP_SELF']);

// Si on est dans un sous-dossier (php/, etc.), on ajuste les chemins
if (strpos($_SERVER['PHP_SELF'], '/php/') !== false) {
    $base_url = '../';
}

// === THÈME ===
$theme = $_SESSION['theme'] ?? 'dark';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LabGenius - <?= $page_title ?? 'Laboratoire' ?></title>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Styles communs -->
    <link rel="stylesheet" href="<?= $base_url ?>css/style.css">
    
    <!-- Style spécifique à la page -->
    <?php if (isset($page_css)): ?>
        <link rel="stylesheet" href="<?= $base_url ?>css/<?= $page_css ?>">
    <?php endif; ?>
</head>
<body data-theme="<?= $theme ?>">
    
    <?php if (isset($_SESSION['user_id'])): ?>
    <!-- Header visible seulement si connecté -->
    <header class="main-header">
        <nav class="main-nav">
            <!-- LOGO AVEC IMAGE -->
            <div class="logo">
                <a href="<?= $base_url ?>index.php">
                  
                  <span>LabGenius</span>
                </a>
            </div>
            
            <!-- MENU PRINCIPAL -->
            <ul class="nav-links">
                <li>
                    <a href="<?= $base_url ?>index.php" class="<?= $current_file == 'index.php' ? 'active' : '' ?>">
                        <i class="fas fa-chart-pie"></i> Dashboard
                    </a>
                </li>
                <li>
                    <a href="<?= $base_url ?>php/sequenceur.php" class="<?= $current_file == 'sequenceur.php' ? 'active' : '' ?>">
                        <i class="fas fa-dna"></i> Séquenceur
                    </a>
                </li>
                <li>
                    <a href="<?= $base_url ?>php/synthese.php" class="<?= $current_file == 'synthese.php' ? 'active' : '' ?>">
                        <i class="fas fa-flask"></i> Synthèse
                    </a>
                </li>
                <li>
                    <a href="<?= $base_url ?>php/bibliotheque.php" class="<?= $current_file == 'bibliotheque.php' ? 'active' : '' ?>">
                        <i class="fas fa-book"></i> Bibliothèque
                    </a>
                </li>
                <li>
                    <a href="<?= $base_url ?>php/carnet.php" class="<?= $current_file == 'carnet.php' ? 'active' : '' ?>">
                        <i class="fas fa-notebook"></i> Carnet
                    </a>
                </li>
            </ul>
            
            <!-- MENU UTILISATEUR -->
            <div class="user-menu">
                <!-- Bascule de thème - Redirige vers theme.php -->
                <a href="<?= $base_url ?>theme.php" class="theme-toggle" title="Changer de thème">
                    <i class="fas <?= $theme === 'dark' ? 'fa-sun' : 'fa-moon' ?>"></i>
                </a>
                
                <!-- Indicateur utilisateur -->
                <div class="user-indicator">
                    <i class="fas fa-user-circle"></i>
                    <span><?= $_SESSION['user_name'] ?? 'Chercheur' ?></span>
                </div>
                
                <!-- Déconnexion -->
                <a href="<?= $base_url ?>logout.php" class="btn-logout" title="Déconnexion">
                    <i class="fas fa-sign-out-alt"></i>
                    <span class="logout-text">Déconnexion</span>
                </a>
            </div>
        </nav>
    </header>
    <?php endif; ?>
    
    <main class="main-content">