// JavaScript pour le Carnet de laboratoire

function ouvrirModal() {
    document.getElementById('noteModal').style.display = 'flex';
}

function fermerModal() {
    document.getElementById('noteModal').style.display = 'none';
}

// Fermer le modal en cliquant dehors
window.addEventListener('click', function(event) {
    const modal = document.getElementById('noteModal');
    if (event.target === modal) {
        fermerModal();
    }
});

// Raccourci clavier Escape
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        const modal = document.getElementById('noteModal');
        if (modal.style.display === 'flex') {
            fermerModal();
        }
    }
});

// Animation d'apparition des notes
document.addEventListener('DOMContentLoaded', function() {
    const notes = document.querySelectorAll('.note-card');
    notes.forEach((note, index) => {
        note.style.animation = `fadeIn 0.3s ease-out ${index * 0.1}s forwards`;
        note.style.opacity = '0';
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