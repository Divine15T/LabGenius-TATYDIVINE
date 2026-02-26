<?php
/** Classe Database - Gestion des données de LabGenius**/
class Database {
    private $data = [];
    private $storage_file;
    
    /* Constructeur */
    public function __construct() {
        $this->storage_file = dirname(__DIR__) . '/data.json';
        $this->charger();
    }
    
    /* Sauvegarde les données */
    private function sauvegarder() {
        $_SESSION['labgenius_data'] = $this->data;
        
        $fp = fopen($this->storage_file, 'w');
        if (flock($fp, LOCK_EX)) {
            fwrite($fp, json_encode($this->data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            flock($fp, LOCK_UN);
        }
        fclose($fp);
    }
    
    /* Charge les données */
    private function charger() {
        // Démarrer la session si nécessaire
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // D'abord, essayer de charger depuis la session
        if (isset($_SESSION['labgenius_data']) && !empty($_SESSION['labgenius_data'])) {
            $this->data = $_SESSION['labgenius_data'];
            return;
        }
        
        // Sinon, essayer de charger depuis le fichier
        if (file_exists($this->storage_file)) {
            $contenu = file_get_contents($this->storage_file);
            $this->data = json_decode($contenu, true);
            if (is_array($this->data) && !empty($this->data)) {
                return;
            }
        }
        
        // Si rien n'existe, on initialise les données par défaut
        $this->initialiserDonnees();
    }
    
    /* Initialise les données par défaut */
    private function initialiserDonnees() {
        $this->data = [
            'sequences' => [
                [
                    'id' => 1,
                    'nom' => 'Gène de fluorescence GFP',
                    'sequence' => 'ATGGCTAGCAAAGGAGAAGAACTTTTCACTGGAGTTGTCCCAATTCTTGTTGAATTAGATGGTGATGTTAATGGGCACAAATTTTCTGTCAGTGGAGAGGGTGAAGGTGATGCAACATACGGAAAACTTACCCTTAAATTTATTTGCACTACTGGAAAACTACCTGTTCCATGGCCAACACTTGTCACTACTTTC',
                    'favori' => false,
                    'date_creation' => date('Y-m-d H:i:s'),
                    'description' => 'Green Fluorescent Protein'
                ],
                [
                    'id' => 2,
                    'nom' => 'Résistance à l\'ampicilline',
                    'sequence' => 'ATGAGTATTCAACATTTCCGTGTCGCCCTTATTCCCTTTTTTGCGGCATTTTGCCTTCCTGTTTTTGCTCACCCAGAAACGCTGGTGAAAGTAAAAGATGCTGAAGATCAGTTGGGTGCACGAGTGGGTTACATCGAACTGGATCTCAACAGCGGTAAGATCCTTGAGAGTTTTCGCCCCGAAGAACGTTTTCCAATGATGAGCACTTTTAAAGTTCTGCTATGTGGCGCGGTATTATCCCGTATTGACGCCGGGCAAGAGCAACTCGGTCGCCGCATACACTATTCTCAGAATGACTTGGTTGAGTACTCACCAGTCACAGAAAAGCATCTTACGGATGGCATGACAGTAAGAGAATTATGCAGTGCTGCCATAACCATGAGTGATAACACTGCGGCCAACTTACTTCTGACAACGATCGGAGGACCGAAGGAGCTAACCGCTTTTTTGCACAACATGGGGGATCATGTAACTCGCCTTGATCGTTGGGAACCGGAGCTGAATGAAGCCATACCAAACGACGAGCGTGACACCACGATGCCTGTAGCAATGGCAACAACGTTGCGCAAACTATTAACTGGCGAACTACTTACTCTAGCTTCCCGGCAACAATTAATAGACTGGATGGAGGCGGATAAAGTTGCAGGACCACTTCTGCGCTCGGCCCTTCCGGCTGGCTGGTTTATTGCTGATAAATCTGGAGCCGGTGAGCGTGGGTCTCGCGGTATCATTGCAGCACTGGGGCCAGATGGTAAGCCCTCCCGTATCGTAGTTATCTACACGACGGGGAGTCAGGCAACTATGGATGAACGAAATAGACAGATCGCTGAGATAGGTGCCTCACTGATTAAGCATTGGTAA',
                    'favori' => false,
                    'date_creation' => date('Y-m-d H:i:s'),
                    'description' => 'Gène de résistance ampicilline'
                ],
                [
                    'id' => 3,
                    'nom' => 'Séquence de contrôle',
                    'sequence' => 'ATGCGTACGTAGCTAGCTAGC',
                    'favori' => false,
                    'date_creation' => date('Y-m-d H:i:s'),
                    'description' => 'Séquence standard'
                ]
            ],
            'notes' => [],
            'journal' => [],
            'projets' => [
                [
                    'id' => 1,
                    'nom' => 'Étude de la fluorescence',
                    'avancement' => 75,
                    'statut' => 'En cours',
                    'date_debut' => date('Y-m-d')
                ]
            ],
            'statistiques' => [
                'total_sequences' => 3,
                'syntheses_reussies' => 0,
                'projets_actifs' => 1
            ]
        ];
        
        $this->sauvegarder();
    }
    
    // === MÉTHODES POUR LES SÉQUENCES ===
    
    public function getToutesSequences() {
        return $this->data['sequences'] ?? [];
    }
    
    public function getSequenceById($id) {
        foreach ($this->data['sequences'] as $sequence) {
            if ($sequence['id'] == $id) {
                return $sequence;
            }
        }
        return null;
    }
    
    public function getFavoris() {
        $favoris = [];
        foreach ($this->data['sequences'] as $seq) {
            if (!empty($seq['favori'])) {
                $favoris[] = $seq;
            }
        }
        return $favoris;
    }
    
    public function toggleFavori($id) {
        foreach ($this->data['sequences'] as &$seq) {
            if ($seq['id'] == $id) {
                $seq['favori'] = !($seq['favori'] ?? false);
                $this->sauvegarder();
                return $seq['favori'];
            }
        }
        return false;
    }
    
    public function ajouterSequence($nom, $sequence, $description = '') {
        $nouvelId = 1;
        if (!empty($this->data['sequences'])) {
            $dernier = end($this->data['sequences']);
            $nouvelId = $dernier['id'] + 1;
        }
        
        $nouvelleSequence = [
            'id' => $nouvelId,
            'nom' => $nom,
            'sequence' => strtoupper($sequence),
            'description' => $description,
            'favori' => false,
            'date_creation' => date('Y-m-d H:i:s')
        ];
        
        $this->data['sequences'][] = $nouvelleSequence;
        $this->sauvegarder();
        $this->ajouterAuJournal('sequence', "Nouvelle séquence: $nom");
        
        return $nouvelId;
    }
    
    public function supprimerSequence($id) {
        $nouvellesSequences = [];
        foreach ($this->data['sequences'] as $seq) {
            if ($seq['id'] != $id) {
                $nouvellesSequences[] = $seq;
            }
        }
        $this->data['sequences'] = $nouvellesSequences;
        $this->sauvegarder();
        $this->ajouterAuJournal('sequence', "Séquence supprimée");
    }
    
    public function getDernieresSequences($limite = 5) {
        $sequences = $this->data['sequences'] ?? [];
        $sequences = array_reverse($sequences);
        return array_slice($sequences, 0, $limite);
    }
    
    // === MÉTHODES POUR LES PROJETS ===
    
    public function getProjetsRecents($limite = 3) {
        return array_slice($this->data['projets'] ?? [], 0, $limite);
    }
    
    // === MÉTHODES POUR LE JOURNAL ===
    
    private function ajouterAuJournal($type, $description, $succes = true) {
        if (!isset($this->data['journal'])) {
            $this->data['journal'] = [];
        }
        
        $this->data['journal'][] = [
            'timestamp' => date('Y-m-d H:i:s'),
            'type' => $type,
            'description' => $description,
            'succes' => $succes
        ];
        
        if (count($this->data['journal']) > 50) {
            array_shift($this->data['journal']);
        }
        
        $this->sauvegarder();
    }
    
    public function getLogsRecents($limite = 10) {
        $journal = array_reverse($this->data['journal'] ?? []);
        return array_slice($journal, 0, $limite);
    }
    
    public function getDernieresExperiences($limite = 10) {
        return $this->getLogsRecents($limite);
    }
    
    // === MÉTHODES POUR LES NOTES ===
    
    public function ajouterNoteLaboratoire($titre, $contenu, $sequence_id = null, $tags = '', $important = false) {
        if (!isset($this->data['notes'])) {
            $this->data['notes'] = [];
        }
        
        $note = [
            'id' => uniqid(),
            'titre' => $titre,
            'contenu' => $contenu,
            'sequence_id' => $sequence_id,
            'tags' => $tags,
            'important' => $important ? 1 : 0,
            'date_creation' => date('Y-m-d H:i:s')
        ];
        
        $this->data['notes'][] = $note;
        $this->sauvegarder();
        $this->ajouterAuJournal('note', "Nouvelle note: $titre");
        
        return $note['id'];
    }
    
    public function getToutesNotes() {
        $notes = $this->data['notes'] ?? [];
        foreach ($notes as &$note) {
            if (!empty($note['sequence_id'])) {
                $seq = $this->getSequenceById($note['sequence_id']);
                $note['sequence_associee'] = $seq['sequence'] ?? null;
            }
        }
        return $notes;
    }
    
    // === MÉTHODES POUR LES STATISTIQUES ===
    
    public function getStatistiques() {
        return [
            'total_sequences' => count($this->data['sequences'] ?? []),
            'syntheses_reussies' => $this->data['statistiques']['syntheses_reussies'] ?? 0,
            'projets_actifs' => count(array_filter($this->data['projets'] ?? [], function($p) {
                return $p['statut'] === 'En cours';
            }))
        ];
    }
}
?>