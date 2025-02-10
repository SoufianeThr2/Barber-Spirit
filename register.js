document.getElementById('registerForm').addEventListener('submit', function (e) {
    e.preventDefault();
    
    // Récupérer les valeurs du formulaire
    var username = document.getElementById('username').value;
    var email = document.getElementById('email').value;
    var password = document.getElementById('password').value;

    // Effectuer des opérations d'inscription ici (par exemple, envoyer une requête au serveur)
    // ...
});
