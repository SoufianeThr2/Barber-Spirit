<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["email"]) && isset($_POST["verification_code"])) {
        $email = $_POST["email"];
        $verification_code = $_POST["verification_code"];

        $servername = "localhost";
        $db_username = "root";
        $db_password = "";
        $dbname = "db_login";

        $conn = new mysqli($servername, $db_username, $db_password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $stmt = $conn->prepare("SELECT verification_code FROM users WHERE email = ? AND is_verified = FALSE");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($db_verification_code);
        $stmt->fetch();
        $stmt->close();

        if ($db_verification_code) { // Ajoutez cette vérification pour éviter les comparaisons nulles
            if ($verification_code == $db_verification_code) {
                $stmt = $conn->prepare("UPDATE users SET is_verified = TRUE WHERE email = ?");
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $stmt->close();

                $response = ["success" => true, "message" => "Votre compte a été vérifié avec succès !"];
            } else {
                $response = ["success" => false, "message" => "Code de vérification incorrect."];
            }
        } else {
            $response = ["success" => false, "message" => "Utilisateur non trouvé ou déjà vérifié."];
        }

        $conn->close();
        echo json_encode($response);
        exit();
    } else {
        $response = ["success" => false, "message" => "Tous les champs doivent être remplis !"];
        echo json_encode($response);
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="stylerelo.css">
    <title>Vérification du code</title>
</head>
<body>
    <div class="container">
        <h2>Vérification du code</h2>
        <form id="verifyForm">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="verification_code">Code de vérification:</label>
            <input type="text" id="verification_code" name="verification_code" required>

            <button type="submit">Vérifier</button>
        </form>

        <div id="message" style="display:none; margin-top: 20px;"></div>
    </div>
    <script>
    document.getElementById('verifyForm').addEventListener('submit', function (e) {
        e.preventDefault();

        var email = document.getElementById('email').value;
        var verification_code = document.getElementById('verification_code').value;

        fetch('./verify.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: 'email=' + encodeURIComponent(email) +
                  '&verification_code=' + encodeURIComponent(verification_code)
        })
        .then(response => response.json())
        .then(data => {
            var messageDiv = document.getElementById('message');
            if (data.success) {
                messageDiv.style.display = 'block';
                messageDiv.style.color = 'green';
                messageDiv.textContent = data.message;
                // Rediriger vers la page de connexion après vérification réussie
                setTimeout(() => {
                    window.location.href = './login.php';
                }, 3000); // Redirection après 3 secondes
            } else {
                messageDiv.style.display = 'block';
                messageDiv.style.color = 'red';
                messageDiv.textContent = data.message;
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
        });
    });
    </script>
</body>
</html>
