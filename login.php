<?php
session_start();
require 'config.php'; // Include the database configuration file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Query the database for the user
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Validate credentials and check if the account is archived
    if ($user && $user['password'] === $password) {
        if ($user['archived']) {
            $error_message = "Votre compte a été supprimé et ne peut pas être accédé.";
        } else {
            $_SESSION["username"] = $username;
            header("Location: userpage.php");
            exit();
        }
    } else {
        $error_message = "Invalid username or password!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="stylerelo.css">
    <title>Login</title>
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <?php if (isset($error_message)): ?>
            <p style="color: red;"><?php echo $error_message; ?></p>
        <?php endif; ?>
        <form id="loginForm" action="login.php" method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" class="form-control" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" class="form-control" required>

            <button type="submit" class="btn btn-primary">Login</button>
        </form>
        <p>Vous n'avez pas de compte ? <a href="register.php">Inscrivez-vous ici</a></p>
    </div>
    <div class="container">
        <!-- Ajout du bouton pour retourner à la page principale -->
        <a href="testing.php" class="back-btn">Retour à la page principale</a>
    </div>
</body>
</html>
