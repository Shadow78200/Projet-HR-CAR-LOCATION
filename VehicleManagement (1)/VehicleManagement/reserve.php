<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

// Si le bouton "Déconnexion" est cliqué
if (isset($_POST['logout'])) {
    // Détruire la session
    session_unset();
    session_destroy();

    // Rediriger vers la page de connexion
    header('Location: index.php');
    exit;
}

if (isset($_GET['id'])) {
    $vehicule_id = $_GET['id'];

    // Récupérer les informations du véhicule
    $stmt = $pdo->prepare("SELECT * FROM vehicules WHERE id = ?");
    $stmt->execute([$vehicule_id]);
    $vehicle = $stmt->fetch();

    if (!$vehicle) {
        die("Véhicule introuvable.");
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $date_reservation = $_POST['date_reservation'];
        $date_retour = $_POST['date_retour'];

        // Validation des dates
        if ($date_reservation < $vehicle['disponible_de'] || $date_retour > $vehicle['disponible_jusqua']) {
            $error = "Les dates doivent être comprises entre " . $vehicle['disponible_de'] . " et " . $vehicle['disponible_jusqua'] . ".";
        } elseif ($date_reservation > $date_retour) {
            $error = "La date de retour doit être postérieure à la date de réservation.";
        } else {
            // Insérer la réservation dans la base de données
            $stmt = $pdo->prepare("INSERT INTO reservations (utilisateur_id, vehicule_id, date_reservation, date_retour) VALUES (?, ?, ?, ?)");
            $stmt->execute([$_SESSION['user_id'], $vehicule_id, $date_reservation, $date_retour]);

            header('Location: history.php');
            exit;
        }
    }
} else {
    header('Location: vehicles.php');
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <?php
        require_once('head.php');
    ?></head>
<body style="background-color: #343a40;">
    <?php
        require_once('header.php');
    ?>
    <div class="container my-5">
        <h1 style="margin-top:110px">Réserver <?= htmlspecialchars($vehicle['marque']) . " " . htmlspecialchars($vehicle['modele']) ?></h1>
        <p>
            <strong>Dates de disponibilité :</strong>
            Du <span style="color: green;"><?= htmlspecialchars($vehicle['disponible_de']) ?></span>
            au <span style="color: green;"><?= htmlspecialchars($vehicle['disponible_jusqua']) ?></span>
        </p>
        <div class="top-bar">
            <?php if (isset($error)): ?>
                <p style="color: red;"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>

            <form method="POST">
                <label>Date de réservation :</label>
                <input type="date" name="date_reservation" required>
                <label>Date de retour :</label>
                <input type="date" name="date_retour" required>
                <button type="submit">Réserver</button>
            </form>
        </div>
    </div>

    <?php
        require_once('footer.php');
    ?>
</body>
</html>