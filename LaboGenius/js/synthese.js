// JavaScript pour la page Synthèse

let simulationEnCours = false;

function chargerSequence() {
    const select = document.getElementById('bibliotheque');
    const option = select.options[select.selectedIndex];
    
    if (option.value) {
        document.querySelector('textarea[name="sequence"]').value = option.dataset.sequence;
        document.querySelector('input[name="nom_experience"]').value = option.dataset.nom;
    }
}

function demarrerSynthese() {
    if (simulationEnCours) return;
    
    const btn = document.getElementById('btn-demarrer');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Synthèse en cours...';
    simulationEnCours = true;
    
    let progress = 0;
    const interval = setInterval(() => {
        progress += 2;
        document.getElementById('progress-bar').style.width = progress + '%';
        document.getElementById('progress-text').textContent = progress + '%';
        
        if (progress >= 100) {
            clearInterval(interval);
            terminerSynthese();
        }
    }, 100);
}

function terminerSynthese() {
    const success = Math.random() > 0.3; // 70% de chance de réussite
    
    const resultat = document.getElementById('resultat');
    resultat.innerHTML = `
        <div class="resultat-card ${success ? 'success' : 'error'}">
            <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                <i class="fas ${success ? 'fa-check-circle' : 'fa-times-circle'}" 
                   style="font-size: 2rem; color: ${success ? 'var(--success)' : 'var(--error)'}"></i>
                <h3 style="color: ${success ? 'var(--success)' : 'var(--error)'}; margin:0;">
                    ${success ? 'Synthèse réussie !' : 'Échec de la synthèse'}
                </h3>
            </div>
            <p style="color: var(--text-secondary);">
                La synthèse est ${success ? 'terminée avec succès' : 'a échoué'}. 
                ${success ? 'La séquence a été correctement assemblée.' : 'Vérifiez la séquence et réessayez.'}
            </p>
        </div>
    `;
    
    // Réinitialiser le bouton
    const btn = document.getElementById('btn-demarrer');
    btn.disabled = false;
    btn.innerHTML = '<i class="fas fa-play"></i> Démarrer la synthèse';
    simulationEnCours = false;
}

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    console.log('Page synthèse chargée');
});