<?php
/* Classe Sequence - Gestion des séquences ADN */

class Sequence {
    private $bases;
    private $longueur;
    
    public function __construct($sequence = '') {
        $this->bases = strtoupper($sequence);
        $this->longueur = strlen($this->bases);
    }
    
    public function getBases() {
        return $this->bases;
    }
    
    public function getLongueur() {
        return $this->longueur;
    }
    
    public function getARNm() {
        return str_replace('T', 'U', $this->bases);
    }
    
    public function getComplementaire() {
        $complement = '';
        $map = ['A' => 'T', 'T' => 'A', 'G' => 'C', 'C' => 'G'];
        
        for ($i = 0; $i < $this->longueur; $i++) {
            $base = $this->bases[$i];
            $complement .= $map[$base] ?? '?';
        }
        
        return $complement;
    }
    
    public function muter($position = null) {
        $basesPossibles = ['A', 'T', 'G', 'C'];
        
        if ($position === null) {
            $position = rand(0, $this->longueur - 1);
        }
        
        if ($position < 0 || $position >= $this->longueur) {
            return ['succes' => false, 'erreur' => 'Position invalide'];
        }
        
        $ancien = $this->bases[$position];
        $nouveau = $ancien;
        
        while ($nouveau === $ancien) {
            $nouveau = $basesPossibles[array_rand($basesPossibles)];
        }
        
        $this->bases[$position] = $nouveau;
        
        return [
            'succes' => true,
            'position' => $position + 1,
            'ancien' => $ancien,
            'nouveau' => $nouveau
        ];
    }
    
    public function insererGene($position, $gene) {
        $gene = strtoupper($gene);
        
        if ($position < 0 || $position > $this->longueur) {
            return ['succes' => false, 'erreur' => 'Position invalide'];
        }
        
        $this->bases = substr($this->bases, 0, $position) . $gene . substr($this->bases, $position);
        $this->longueur = strlen($this->bases);
        
        return ['succes' => true, 'nouvelle_longueur' => $this->longueur];
    }
    
    public function getComposition() {
        $comp = ['A' => 0, 'T' => 0, 'G' => 0, 'C' => 0];
        
        for ($i = 0; $i < $this->longueur; $i++) {
            $base = $this->bases[$i];
            if (isset($comp[$base])) {
                $comp[$base]++;
            }
        }
        
        return $comp;
    }
    
    public function getPourcentageGC() {
        $comp = $this->getComposition();
        $totalGC = $comp['G'] + $comp['C'];
        
        if ($this->longueur === 0) return 0;
        
        return round(($totalGC / $this->longueur) * 100, 1);
    }
}
?>