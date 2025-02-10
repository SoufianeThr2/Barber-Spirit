<?php
include('includes/db_connect.php');
// Récupérer les réservations depuis la base de données
$reservations = $conn->query("SELECT * FROM reservations");

// Vérifier si la requête est de type POST (mise à jour de l'état de la réservation)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reservation_id']) && isset($_POST['status'])) {
    $reservationId = $_POST['reservation_id'];
    $status = $_POST['status'];

    // Mettre à jour l'état de la réservation dans la base de données
    $updateStmt = $conn->prepare("UPDATE reservations SET status = ? WHERE id = ?");
    $updateStmt->bind_param("si", $status, $reservationId);
    $updateStmt->execute();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Barber Spirit Admin Dashboard</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-icons.css" rel="stylesheet">
    <link href="./css/admin.css" rel="stylesheet">
    <link href="./css/testingcss.css" rel="stylesheet">
    <link href="./css/barber-dashboard.css" rel="stylesheet">
    
    <style>
        /* Ajoutez vos styles personnalisés ici */
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

        /* Styles pour la page Barber Dashboard */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        form {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        select {
            padding: 5px;
        }

        button {
            padding: 5px 10px;
            background-color: #dc3545;
            color: #fff;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }

        button:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <h1>Barber Dashboard</h1>
    <table>
        <thead>
            <tr>
                <th>Nom</th>
                <th>Téléphone</th>
                <th>Heure</th>
                <th>Date</th>
                <th>Nombre de personnes</th>
                <th>Coiffeur</th>
                <th>Services</th>
                <th>Message</th>
                <th>Statut</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $reservations->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['phone']; ?></td>
                    <td><?php echo $row['time']; ?></td>
                    <td><?php echo $row['date']; ?></td>
                    <td><?php echo $row['number']; ?></td>
                    <td><?php echo $row['barber']; ?></td>
                    <td><?php echo $row['services']; ?></td>
                    <td><?php echo $row['message']; ?></td>
                    <td><?php echo $row['status']; ?></td>
                    <td>
                        <form action="" method="post">
                            <input type="hidden" name="reservation_id" value="<?php echo $row['id']; ?>">
                            <select name="status">
                                <option value="En attente">En attente</option>
                                <option value="Terminée">Terminée</option>
                                <option value="Annulée">Annulée</option>
                                <option value="En cours de coiffure">En cours de coiffure</option>
                            </select>
                            <button type="submit">Mettre à jour</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
