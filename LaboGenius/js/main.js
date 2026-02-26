// Script principal LabGenius

// Gestionnaire pour le thème
document.addEventListener('DOMContentLoaded', function() {
    const theme = document.body.dataset.theme || 'dark';
    document.documentElement.setAttribute('data-theme', theme);
});

// Confirmation avant actions
function confirmAction(message) {
    return confirm(message || 'Êtes-vous sûr ?');
}