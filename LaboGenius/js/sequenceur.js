// JavaScript pour le séquenceur

let vueActuelle = 'lesdeux';
let historique = [];

function changerVue(vue) {
    vueActuelle = vue;
    
    document.querySelectorAll('.view-option').forEach(btn => {
        btn.classList.remove('active');
    });
    event.target.classList.add('active');
    
    const texte = document.getElementById('vue-texte');
    const graphique = document.getElementById('vue-graphique');
    
    switch(vue) {
        case 'texte':
            texte.style.display = 'block';
            graphique.style.display = 'none';
            break;
        case 'graphique':
            texte.style.display = 'none';
            graphique.style.display = 'block';
            break;
        case 'lesdeux':
            texte.style.display = 'block';
            graphique.style.display = 'block';
            break;
    }
}

function muterPosition(position) {
    const bases = ['A', 'T', 'G', 'C'];
    const nouvelleBase = bases[Math.floor(Math.random() * 4)];
    
    // Mettre à jour la vue texte
    const texte = document.getElementById('vue-texte');
    let sequence = texte.textContent;
    sequence = sequence.substring(0, position-1) + nouvelleBase + sequence.substring(position);
    texte.textContent = sequence;
    
    // Mettre à jour la vue graphique
    const basesGraphiques = document.querySelectorAll('.base-graphique');
    const baseCiblee = basesGraphiques[position-1];
    baseCiblee.className = `base-graphique base-${nouvelleBase.toLowerCase()}`;
    baseCiblee.querySelector('.lettre').textContent = nouvelleBase;
    baseCiblee.setAttribute('data-base', nouvelleBase);
    
    // Ajouter à l'historique
    ajouterHistorique(`Mutation à la position ${position} → ${nouvelleBase}`);
}

function muterAleatoire() {
    const bases = document.querySelectorAll('.base-graphique');
    const position = Math.floor(Math.random() * bases.length) + 1;
    muterPosition(position);
}

function ouvrirModalInsertion() {
    document.getElementById('modal-insertion').style.display = 'flex';
}

function fermerModal() {
    document.getElementById('modal-insertion').style.display = 'none';
}

function insererGene() {
    const position = parseInt(document.getElementById('position-insertion').value);
    const gene = document.getElementById('gene-sequence').value.toUpperCase();
    
    if (!gene.match(/^[ATGC]+$/)) {
        alert('Séquence invalide. Utilisez A,T,G,C uniquement');
        return;
    }
    
    // Mettre à jour la vue texte
    const texte = document.getElementById('vue-texte');
    let sequence = texte.textContent;
    sequence = sequence.substring(0, position) + gene + sequence.substring(position);
    texte.textContent = sequence;
    
    // Reconstruire la vue graphique (simplifié)
    location.reload(); // Rechargement temporaire
    
    ajouterHistorique(`Insertion de ${gene.length} bases à la position ${position}`);
    fermerModal();
}

function ajouterHistorique(action) {
    const date = new Date();
    const time = `${date.getHours().toString().padStart(2,'0')}:${date.getMinutes().toString().padStart(2,'0')}:${date.getSeconds().toString().padStart(2,'0')}`;
    
    const list = document.getElementById('history-list');
    
    // Supprimer le message vide s'il existe
    if (list.children.length === 1 && list.children[0].classList.contains('empty-state')) {
        list.innerHTML = '';
    }
    
    const item = document.createElement('div');
    item.className = 'history-item';
    item.innerHTML = `
        <span class="history-time">${time}</span>
        <span class="history-action">${action}</span>
    `;
    
    list.insertBefore(item, list.firstChild);
    
    // Limiter à 15 entrées
    while (list.children.length > 15) {
        list.removeChild(list.lastChild);
    }
}

function resetSequence() {
    if (confirm('Réinitialiser la séquence ?')) {
        document.getElementById('vue-texte').textContent = 'ATGCGTACGTAGCTAGCTAGC';
        location.reload(); // Rechargement temporaire
    }
}

function sauvegarder() {
    alert('Fonction de sauvegarde à venir');
}

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    changerVue('lesdeux');
});