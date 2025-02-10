<?php
session_start();
include('db_connect.php');

// Vérifier si l'utilisateur est connecté en tant qu'administrateur
if (!isset($_SESSION['admin_username']) || $_SESSION['admin_username'] !== 'soufiane') {
    echo "Unauthorized"; // Retourner un message d'erreur si l'utilisateur n'est pas autorisé
    exit;
}

// Vérifier si la méthode de requête est POST et si un paramètre 'id' est défini dans la requête
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['id'])) {
    $userId = intval($_POST['id']); // Sécuriser l'entrée utilisateur

    // Préparer la requête SQL pour supprimer l'utilisateur de la table 'users'
    $stmt = $conn->prepare("DELETE FROM users WHERE id=?");
    $stmt->bind_param("i", $userId); // Lier le paramètre à la déclaration

    if ($stmt->execute()) {
        echo "Success";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close(); // Fermer la déclaration
} else {
    echo "Invalid request";
}
?>
