<?php
session_start();
include('includes/db_connect.php');

// Vérifier si l'utilisateur est connecté en tant qu'administrateur
if (!isset($_SESSION['admin_username']) || $_SESSION['admin_username'] !== 'soufiane') {
    echo "Unauthorized"; // Retourner un message d'erreur si l'utilisateur n'est pas autorisé
    exit;
}

// Vérification de l'archivage d'utilisateur
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['delete_user_id'])) {
    $userId = intval($_POST['delete_user_id']); // Sécuriser l'entrée utilisateur

    // Préparer la requête SQL pour archiver l'utilisateur dans la table 'users'
    $stmt = $conn->prepare("UPDATE users SET archived=TRUE WHERE id=?");
    $stmt->bind_param("i", $userId); // Lier le paramètre à la déclaration

    if ($stmt->execute()) {
        echo "L'utilisateur a été supprimé avec succès.";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close(); // Fermer la déclaration
}

// Vérification de la suppression de réservation
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['delete_reservation_id'])) {
    $reservationId = intval($_POST['delete_reservation_id']); // Sécuriser l'entrée utilisateur

    // Préparer la requête SQL pour supprimer la réservation de la table 'reservations'
    $stmt = $conn->prepare("DELETE FROM reservations WHERE id=?");
    $stmt->bind_param("i", $reservationId); // Lier le paramètre à la déclaration

    if ($stmt->execute()) {
        echo "Reservation deleted successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close(); // Fermer la déclaration
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Barber Spirit Admin Dashboard</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-icons.css" rel="stylesheet">
    <link href="./css/admin.css" rel="stylesheet">
    <link href="./css/testingcss.css" rel="stylesheet">
    <style>
        /* Ajoutez votre style personnalisé ici */
        .custom-navbar {
            background-color: #343a40; /* Noir */
        }
        .custom-navbar-brand,
        .custom-navbar-nav .custom-nav-link {
            color: #ffffff !important; /* Blanc */
        }
        .custom-navbar-toggler-icon {
            background-color: #ffffff; /* Blanc */
        }
        .custom-navbar-nav {
            margin-right: 0; /* Retirez la marge de droite */
        }
        .custom-logo-image {
            max-width: 80px; /* Largeur maximale du logo */
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark custom-navbar">
        <div class="container-fluid">
            <a class="navbar-brand custom-navbar-brand" href="admin_dashboard.php">
                <img src="./images/logo5-modified.png" class="custom-logo-image img-fluid" alt="Barber Spirit Logo">
            </a>
            <button class="navbar-toggler custom-navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#custom-navbarNav" aria-controls="custom-navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="custom-navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="custom-navbarNav">
                <ul class="navbar-nav custom-navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link custom-nav-link" href="admin_login.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container mt-4">
        <h1>Barber Spirit Admin Dashboard</h1>
        <h2>Réservations</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Téléphone</th>
                    <th>Heure</th>
                    <th>Date</th>
                    <th>Nombre de personnes</th>
                    <th>Coiffeur</th>
                    <th>Services</th> <!-- Ajout de la colonne des services -->
                    <th>Message</th>
                    <th>Statut</th> <!-- Ajout de la colonne de statut -->
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Sélectionner toutes les réservations
                $result = $conn->query("SELECT * FROM reservations");
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . $row['name'] . "</td>";
                    echo "<td>" . $row['phone'] . "</td>";
                    echo "<td>" . $row['time'] . "</td>";
                    echo "<td>" . $row['date'] . "</td>";
                    echo "<td>" . $row['number'] . "</td>";
                    echo "<td>" . $row['barber'] . "</td>";
                    echo "<td>" . $row['services'] . "</td>"; // Afficher les services
                    echo "<td>" . $row['message'] . "</td>";
                    echo "<td>" . $row['status'] . "</td>";
                    echo "<td><form action='' method='post'><input type='hidden' name='delete_reservation_id' value='" . $row['id'] . "'><button type='submit' class='btn btn-danger' onclick='return confirm(\"Are you sure you want to delete this reservation?\")'>Supprimer</button></form
                    ></td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <div class="container mt-4">
        <h2>Suppression d'utilisateur</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom d'utilisateur</th>
                    <th>Date d'inscription</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php
            // Sélectionnez tous les utilisateurs non archivés
            $result = $conn->query("SELECT id, username, registration_date FROM users WHERE archived=FALSE");
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . $row['username'] . "</td>";
                echo "<td>" . $row['registration_date'] . "</td>";
                echo "<td><form action='' method='post'><input type='hidden' name='delete_user_id' value='" . $row['id'] . "'><button type='submit' class='btn btn-danger' onclick='return confirm(\"Are you sure you want to delete this user?\")'>Supprimer</button></form></td>";
                echo "</tr>";
            }
            ?>
            </tbody>
        </table>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $(".delete-user").click(function(e) {
                e.preventDefault();
                console.log("Delete button clicked"); // Ajout du console.log pour le débogage
                var userId = $(this).data('id');
                if (confirm("Are you sure you want to delete this user?")) {
                    $.ajax({
                        url: 'includes/delete_user.php', // Change the URL to the PHP script that handles the deletion
                        type: 'POST',
                        data: {id: userId},
                        success: function(response) {
                            // Reload the page after successful deletion
                            location.reload();
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                            alert("An error occurred while deleting the user. Please try again later.");
                        }
                    });
                }
            });

            $(".delete-reservation").click(function(e) {
                e.preventDefault();
                console.log("Delete button clicked"); // Ajout du console.log pour le débogage
                var reservationId = $(this).data('id');
                if (confirm("Are you sure you want to delete this reservation?")) {
                    $.ajax({
                        url: 'includes/delete_reservation.php', // Change the URL to the PHP script that handles the deletion
                        type: 'POST',
                        data: {id: reservationId},
                        success: function(response) {
                            // Recharger la page après la suppression réussie
                            location.reload();
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                            alert("Une erreur s'est produite lors de la suppression de la réservation. Veuillez réessayer plus tard.");
                        }
                    });
                }
            });
        });
    </script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/list.js"></script>
</body>
</html>
