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

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_reservation'])) {
    $reservation_id = $_POST['reservation_id'];

    // Supprimer les réservations associées
    $stmt = $pdo->prepare("DELETE FROM reservations WHERE id = ?");
    $stmt->execute([$reservation_id]);

    // Rediriger pour éviter la soumission du formulaire en rafraîchissant la page
    header('Location: history.php');
    exit;
}



$stmt = $pdo->prepare("
    SELECT r.id, v.marque, v.modele, r.date_reservation, r.date_retour
    FROM reservations r
    JOIN vehicules v ON r.vehicule_id = v.id
    WHERE r.utilisateur_id = ?
");
$stmt->execute([$_SESSION['user_id']]);
$reservations = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <?php
        require_once('head.php');
    ?>
</head>

<body style="background-color: #343a40;">
    <?php
        require_once('header.php');
    ?>
    <h1 style="margin-top:110px">Historique de vos réservations</h1>

    <div class="top-bar">
        <ul>
            <?php foreach ($reservations as $reservation): ?>
                <li>
                    <?= $reservation['marque'] . " " . $reservation['modele'] ?>
                    - Réservé du <?= $reservation['date_reservation'] ?> au <?= $reservation['date_retour'] ?>
                </li>
                <form method="POST" class="delete-form">
                    <input type="hidden" name="reservation_id" value="<?= $reservation['id'] ?>">
                    <button type="submit" name="delete_reservation" class="delete-button">Annuler</button>
                </form>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php
        require_once('footer.php');
    ?>
</body>
</html>
