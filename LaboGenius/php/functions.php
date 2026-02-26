<?php
/* Fonctions utilitaires - LabGenius */

function validerSequence($sequence) {
    return preg_match('/^[ATGCatgc]+$/', $sequence);
}

function colorerSequence($sequence) {
    $html = '<span class="dna-sequence">';
    
    for ($i = 0; $i < strlen($sequence); $i++) {
        $base = $sequence[$i];
        $classe = 'base-' . strtolower($base);
        $html .= "<span class='base $classe'>$base</span>";
    }
    
    $html .= '</span>';
    return $html;
}

function formaterDate($date) {
    return date('d/m/Y H:i', strtotime($date));
}

function tronquerTexte($texte, $longueur = 50) {
    if (strlen($texte) <= $longueur) {
        return $texte;
    }
    return substr($texte, 0, $longueur) . '...';
}

function getInitiales($nom) {
    $mots = explode(' ', $nom);
    $initiales = '';
    
    foreach ($mots as $mot) {
        if (!empty($mot)) {
            $initiales .= strtoupper($mot[0]);
        }
    }
    
    return substr($initiales, 0, 2);
}

function afficherMessage($message, $type = 'info') {
    $icones = [
        'success' => 'fa-check-circle',
        'error' => 'fa-exclamation-circle',
        'warning' => 'fa-exclamation-triangle',
        'info' => 'fa-info-circle'
    ];
    
    $icone = $icones[$type] ?? $icones['info'];
    
    return "<div class='message $type'>
                <i class='fas $icone'></i>
                <span>" . htmlspecialchars($message) . "</span>
            </div>";
}

function logger($message, $type = 'info') {
    $logFile = dirname(__DIR__) . '/logs/app.log';
    $date = date('Y-m-d H:i:s');
    $log = "[$date] [$type] $message" . PHP_EOL;
    
    // Créer le dossier logs s'il n'existe pas
    $logDir = dirname($logFile);
    if (!is_dir($logDir)) {
        mkdir($logDir, 0755, true);
    }
    
    file_put_contents($logFile, $log, FILE_APPEND);
}

function getMicrotime() {
    return microtime(true);
}

function calculerTempsExecution($debut) {
    return round(microtime(true) - $debut, 3);
}

function nettoyerInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function genererToken() {
    return bin2hex(random_bytes(32));
}

function verifierToken($token, $session_token) {
    return hash_equals($session_token, $token);
}
?>