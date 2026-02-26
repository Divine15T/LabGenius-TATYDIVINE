// JavaScript pour la Bibliothèque

function filtrer(type) {
    // Mettre à jour les boutons actifs
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    event.target.classList.add('active');
    
    const cartes = document.querySelectorAll('.sequence-card');
    
    cartes.forEach(carte => {
        switch(type) {
            case 'all':
                carte.style.display = 'block';
                break;
            case 'favoris':
                if (carte.dataset.favori === 'true') {
                    carte.style.display = 'block';
                } else {
                    carte.style.display = 'none';
                }
                break;
        }
    });
}

function chargerDansSequenceur(id, sequence, nom) {
    // Rediriger vers le séquenceur avec l'ID
    window.location.href = `sequenceur.php?id=${id}`;
}

// Animation d'apparition des cartes
document.addEventListener('DOMContentLoaded', function() {
    const cartes = document.querySelectorAll('.sequence-card');
    cartes.forEach((carte, index) => {
        carte.style.animation = `fadeIn 0.3s ease-out ${index * 0.1}s forwards`;
        carte.style.opacity = '0';
    });
});

// Animation fadeIn
const style = document.createElement('style');
style.textContent = `
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
`;
document.head.appendChild(style);