<?php
// Inclure les fichiers de configuration et PHPMailer
require './includes/db_connect.php';
require './PHPMailer-master/PHPMailer-master/src/Exception.php';
require './PHPMailer-master/PHPMailer-master/src/SMTP.php';
require './PHPMailer-master/PHPMailer-master/src/PHPMailer.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Fonction pour envoyer un e-mail de notification
function sendNotificationEmail($orderData) {
    // Configuration de PHPMailer
    $mail = new PHPMailer(true);
    try {
        // Configuration de l'envoi SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Remplacez par le nom de votre serveur SMTP
        $mail->SMTPAuth = true;
        $mail->Username = 'barberspirit01@gmail.com'; // Remplacez par votre adresse e-mail
        $mail->Password = 'bvgs rquu kxhm kdft'; // Remplacez par votre mot de passe e-mail
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Destinataire de la notification
        $mail->setFrom('barberspirit01@gmail.com', 'Réservation Barber Spirit'); // Remplacez par votre adresse e-mail et le nom de votre site
        $mail->addAddress('barberspirit01@gmail.com', 'Administrator'); // Remplacez par l'adresse e-mail de l'administrateur

        // Contenu de l'e-mail
        $mail->isHTML(true);
        $mail->Subject = 'Nouvelle réservation';
        $mail->Body = $orderData;

        // Envoyer l'e-mail
        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['bb-name'];
    $phone = $_POST['bb-phone'];
    $time = $_POST['bb-time'];
    $date = $_POST['bb-date'];
    $number = $_POST['bb-number'];
    $barber = $_POST['barber1'];
    $services = isset($_POST['services']) ? implode(', ', $_POST['services']) : 'Aucun service sélectionné';
    $message = $_POST['bb-message'];

    // Insérer les données dans la base de données
    $sql = "INSERT INTO reservations (name, phone, time, date, number, barber, services, message)
            VALUES ('$name', '$phone', '$time', '$date', '$number', '$barber', '$services', '$message')";

    if ($conn->query($sql) === TRUE) {
        $orderData = "
            <html>
            <head>
                <style>
                    table {
                        font-family: Arial, sans-serif;
                        border-collapse: collapse;
                        width: 100%;
                    }
                    th, td {
                        border: 1px solid #dddddd;
                        text-align: left;
                        padding: 8px;
                    }
                    th {
                        background-color: #f2f2f2;
                    }
                </style>
            </head>
            <body>
                <h2>Nouvelle réservation</h2>
                <table>
                    <tr>
                        <th>Nom</th>
                        <td>$name</td>
                    </tr>
                    <tr>
                        <th>Téléphone</th>
                        <td>$phone</td>
                    </tr>
                    <tr>
                        <th>Heure</th>
                        <td>$time</td>
                    </tr>
                    <tr>
                        <th>Date</th>
                        <td>$date</td>
                    </tr>
                    <tr>
                        <th>Nombre de personnes</th>
                        <td>$number</td>
                    </tr>
                    <tr>
                        <th>Coiffeur</th>
                        <td>$barber</td>
                    </tr>
                    <tr>
                        <th>Services</th>
                        <td>$services</td>
                    </tr>
                    <tr>
                        <th>Message</th>
                        <td>$message</td>
                    </tr>
                </table>
            </body>
            </html>";

            if (sendNotificationEmail($orderData)) {
                echo 'Votre réservation a été validée avec succès.';
            } else {
                echo 'Échec de la réservation.';
            }
    } else {
        echo "Erreur : " . $sql . "<br>" . $conn->error;
    }

    // Fermer la connexion à la base de données
    $conn->close();
}
?>



<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="description" content="">
    <meta name="author" content="">

    <title>Réservation</title>

    <!-- CSS FILES -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Unbounded:wght@300;500&display=swap" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-icons.css" rel="stylesheet">
    <link href="./css/testingcss.css" rel="stylesheet">
</head>
<body>
    <section class="booking-section section-padding" id="booking-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 col-12 mx-auto">
                    <form action="#" method="post" class="custom-form booking-form" id="bb-booking-form" role="form">
                        <div class="text-center mb-5">
                            <h2 class="mb-1">Réservez une place</h2>
                            <p>Veuillez remplir le formulaire et nous vous répondrons rapidement</p>
                        </div>
                        <div class="booking-form-body">
                            <div class="row">
                                <div class="col-lg-6 col-12">
                                    <input type="text" name="bb-name" id="bb-name" class="form-control" placeholder="Nom complet" required>
                                </div>
                                <div class="col-lg-6 col-12">
                                    <input type="tel" class="form-control" name="bb-phone" placeholder="Mobile +212 6 00 00 00 00" pattern="[0-9]{6}-[0-9]-{0-9}-[0-9]-{0-9}" required="">
                                </div>
                                <div class="col-lg-6 col-12">
                                    <input class="form-control" type="time" name="bb-time" value="18:30" />
                                </div>
                                <div class="col-lg-6 col-12">
                                    <input type="number" name="bb-number" id="bb-number" class="form-control" placeholder="Nombre de personnes" required>
                                </div>
                                <div class="col-lg-6 col-12">
                                    <input type="date" name="bb-date" id="bb-date" class="form-control" placeholder="Date" required>
                                </div>
                            </div>
                            
                            <div class="row mt-3">
                                <div class="col-12">
                                    <label class="form-label">Services</label>
                                </div>
                                <div class="col-lg-6 col-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="services[]" id="service1" value="Coupe de cheveux">
                                        <label class="form-check-label" for="service1">Coupe de cheveux</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="services[]" id="service2" value="Rasage">
                                        <label class="form-check-label" for="service2">Rasage</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="services[]" id="service3" value="Shampooing">
                                        <label class="form-check-label" for="service3">Shampooing</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="services[]" id="service4" value="Soin du visage">
                                        <label class="form-check-label" for="service4">Soin du visage</label>
                                    </div>
                                    <!-- Ajoutez d'autres services si nécessaire -->
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-lg-6 col-12">
                                    <label for="barber1" class="form-label">Coiffeur</label>
                                    <select class="form-select form-control" name="barber1" id="barber1" aria-label="Choose Barber 1">
                                        <option selected disabled>Choisissez un coiffeur</option>
                                        <option value="Hamza">Hamza</option>
                                        <option value="Ahmed">Ahmed</option>
                                        <!-- Ajoutez d'autres options si nécessaire -->
                                    </select>
                                </div>
                            </div>
                            
                            <div class="row mt-3">
                                <textarea name="bb-message" rows="3" class="form-control" id="bb-message" placeholder="Commentaires (Optionnels)"></textarea>
                            </div>
                            
                            <div class="col-lg-4 col-md-10 col-8 mx-auto mt-3">
                                <button type="submit" class="form-control">Soumettre</button>
                            </div>
                        </div>
                    </form>
                    <!-- Bouton pour retourner à la page principale -->
                    <div class="text-center mt-3">
                        <a href="userpage.php" class="btn btn-primary">Retourner à la page principale</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- JAVASCRIPT FILES -->
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/click-scroll.js"></script>
    <script src="js/custom.js"></script>
</body>
</html>
