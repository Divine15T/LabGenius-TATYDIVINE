// Fonction pour afficher/masquer le mot de passe
function togglePassword() {
    const password = document.getElementById('password');
    const icon = event.currentTarget.querySelector('i');
    
    if (password.type === 'password') {
        password.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        password.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

// Empêcher la soumission du formulaire si vide
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('.login-form');
    if (form) {
        form.addEventListener('submit', function(e) {
            const username = document.querySelector('input[name="username"]').value.trim();
            const password = document.querySelector('input[name="password"]').value.trim();
            
            if (!username || !password) {
                e.preventDefault();
                alert('Veuillez remplir tous les champs');
            }
        });
    }
});
