<?php
require './PHPMailer-master/PHPMailer-master/src/Exception.php';
require './PHPMailer-master/PHPMailer-master/src/SMTP.php';
require './PHPMailer-master/PHPMailer-master/src/PHPMailer.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Vérification des données d'inscription
    if (isset($_POST["username"]) && isset($_POST["email"]) && isset($_POST["password"])) {
        $username = $_POST["username"];
        $email = $_POST["email"];
        $password = $_POST["password"];

        // Génération d'un code de vérification à 6 chiffres
        $verification_code = rand(100000, 999999);

        // Connexion à la base de données
        $servername = "localhost";
        $db_username = "root";
        $db_password = "";
        $dbname = "db_login";

        $conn = new mysqli($servername, $db_username, $db_password, $dbname);

        if ($conn->connect_error) {
            $response = ["success" => false, "message" => "Erreur de connexion à la base de données: " . $conn->connect_error];
        } else {
            // Vérifiez si le nom d'utilisateur ou l'email existe déjà
            $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
            $stmt->bind_param("ss", $username, $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // L'utilisateur existe déjà
                $response = ["success" => false, "message" => "Le nom d'utilisateur ou l'email est déjà utilisé."];
            } else {
                // Préparation de la requête d'insertion
                $stmt = $conn->prepare("INSERT INTO users (username, email, password, verification_code) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("sssi", $username, $email, $password, $verification_code);

                if ($stmt->execute()) {
                    // Envoi de l'e-mail de vérification
                    $mail = new PHPMailer(true);
                    try {
                        // Configuration de l'envoi SMTP
                        $mail->isSMTP();
                        $mail->Host = 'smtp.gmail.com';
                        $mail->SMTPAuth = true;
                        $mail->Username = 'barberspirit01@gmail.com';
                        $mail->Password = 'rqtz seaj oxxg idxf';
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                        $mail->Port = 587;

                        // Destinataire
                        $mail->setFrom('barberspirit01@gmail.com', 'Barber Spirit'); // Adresse d'expéditeur
                        $mail->addAddress($email, $username); // Adresse du destinataire

                        // Contenu de l'e-mail
                        $mail->isHTML(true);
                        $mail->Subject = 'Code de vérification';
                        $mail->Body = 'Votre code de vérification est : ' . $verification_code;

                        $mail->send();

                        $response = ["success" => true, "message" => "Un code de vérification a été envoyé à votre email."];
                    } catch (Exception $e) {
                        $response = ["success" => false, "message" => "Erreur lors de l'envoi de l'email de vérification: " . $mail->ErrorInfo];
                    }
                } else {
                    $response = ["success" => false, "message" => "Erreur lors de l'inscription: " . $stmt->error];
                }
            }

            $stmt->close();
            $conn->close();
        }
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
    <title>Register</title>
</head>
<body>
    <div class="container">
        <h2>Register</h2>
        <form id="registerForm" action="verify.php" method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Register</button>
        </form>

        <p>Already have an account? <a href="login.php">Login here</a></p>
        <div id="message" style="display:none; margin-top: 20px;"></div>
    </div>
    <div class="container">
        <a href="testing.php" class="back-btn">Retour à la page principale</a>
    </div>

    <script>
    document.getElementById('registerForm').addEventListener('submit', function (e) {
        e.preventDefault();

        var username = document.getElementById('username').value;
        var email = document.getElementById('email').value;
        var password = document.getElementById('password').value;

        fetch('register.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: 'username=' + encodeURIComponent(username) +
                  '&email=' + encodeURIComponent(email) +
                  '&password=' + encodeURIComponent(password)
        })
        .then(response => response.json())
        .then(data => {
            var messageDiv = document.getElementById('message');
            if (data.success) {
                messageDiv.style.display = 'block';
                messageDiv.style.color = 'green';
                messageDiv.textContent = 'Inscription réussie ! Un code de vérification a été envoyé à votre email.';
                // Rediriger vers la page verify.php après 3 secondes
                setTimeout(() => {
                    window.location.href = './verify.php';
                }, 3000); // 3 seconds delay
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
