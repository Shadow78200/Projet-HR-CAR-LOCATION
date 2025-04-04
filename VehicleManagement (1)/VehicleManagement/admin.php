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

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_vehicle'])) {
    $vehicle_id = $_POST['vehicle_id'];

    // Supprimer les réservations associées
    $stmt = $pdo->prepare("DELETE FROM reservations WHERE vehicule_id = ?");
    $stmt->execute([$vehicle_id]);

    // Supprimer le véhicule
    $stmt = $pdo->prepare("DELETE FROM vehicules WHERE id = ?");
    $stmt->execute([$vehicle_id]);

    // Rediriger pour éviter la soumission du formulaire en rafraîchissant la page
    header('Location: admin.php');
    exit;
}

// Ajouter un véhicule
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_vehicle'])) {
    $marque = $_POST['marque'];
    $modele = $_POST['modele'];
    $prix_par_jour = $_POST['prix_par_jour'];
    $disponible_de = $_POST['disponible_de'];
    $disponible_jusqua = $_POST['disponible_jusqua'];

    $imagePath = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $targetDir = "uploads/";
        $imagePath = $targetDir . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $imagePath);
    }


    $stmt = $pdo->prepare("
        INSERT INTO vehicules (marque, modele, prix_par_jour, disponible_de, disponible_jusqua, image)
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([$marque, $modele, $prix_par_jour, $disponible_de, $disponible_jusqua, $imagePath]);

    header('Location: admin.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_reservation'])) {
    $reservation_id = $_POST['reservation_id'];

    // Supprimer la réservation
    $stmt = $pdo->prepare("DELETE FROM reservations WHERE id = ?");
    $stmt->execute([$reservation_id]);

    // Rediriger pour éviter la soumission du formulaire en rafraîchissant la page
    header('Location: admin.php');
    exit;
}

// Gestion de la modification d'un véhicule
$edit_vehicle = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_vehicle'])) {
    $vehicle_id = $_POST['vehicle_id'];

    // Récupérer les informations actuelles du véhicule à modifier
    $stmt = $pdo->prepare("SELECT * FROM vehicules WHERE id = ?");
    $stmt->execute([$vehicle_id]);
    $edit_vehicle = $stmt->fetch();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_vehicle'])) {
    $vehicle_id = $_POST['vehicle_id'];
    $marque = $_POST['marque'];
    $modele = $_POST['modele'];
    $prix_par_jour = $_POST['prix_par_jour'];
    $disponible_de = $_POST['disponible_de'];
    $disponible_jusqua = $_POST['disponible_jusqua'];

    $imagePath = $_POST['current_image']; // Conserver l'image actuelle par défaut
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $targetDir = "uploads/";
        $imagePath = $targetDir . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $imagePath);
    }

    // Mettre à jour le véhicule dans la base de données
    $stmt = $pdo->prepare("
        UPDATE vehicules 
        SET marque = ?, modele = ?, prix_par_jour = ?, disponible_de = ?, disponible_jusqua = ?, image = ?
        WHERE id = ?
    ");
    $stmt->execute([$marque, $modele, $prix_par_jour, $disponible_de, $disponible_jusqua, $imagePath, $vehicle_id]);

    // Rediriger pour éviter la soumission répétée du formulaire
    header('Location: admin.php');
    exit;
}



// Récupérer tous les véhicules
$stmt = $pdo->query("SELECT * FROM vehicules");
$vehicles = $stmt->fetchAll();

// Récupérer toutes les réservations
$stmt = $pdo->query("
    SELECT r.id, r.date_reservation, r.date_retour, v.marque, v.modele, u.nom AS utilisateur
    FROM reservations r
    JOIN vehicules v ON r.vehicule_id = v.id
    JOIN utilisateurs u ON r.utilisateur_id = u.id
");
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
    
    <h1 style="margin-top:110px">Dashboard Administrateur</h1>

    <?php if ($edit_vehicle): ?>
        <h2>Modifier le véhicule</h2>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="vehicle_id" value="<?= $edit_vehicle['id'] ?>">
            <input type="hidden" name="current_image" value="<?= htmlspecialchars($edit_vehicle['image']) ?>">

            <label>Marque :</label>
            <input type="text" name="marque" value="<?= htmlspecialchars($edit_vehicle['marque']) ?>" required>

            <label>Modèle :</label>
            <input type="text" name="modele" value="<?= htmlspecialchars($edit_vehicle['modele']) ?>" required>

            <label>Prix par jour :</label>
            <input type="number" name="prix_par_jour" value="<?= htmlspecialchars($edit_vehicle['prix_par_jour']) ?>" required>

            <label>Disponible de :</label>
            <input type="date" name="disponible_de" value="<?= htmlspecialchars($edit_vehicle['disponible_de']) ?>" required>

            <label>Disponible jusqu'à :</label>
            <input type="date" name="disponible_jusqua" value="<?= htmlspecialchars($edit_vehicle['disponible_jusqua']) ?>" required>

            <label>Image :</label>
            <input type="file" name="image" accept="image/*">
            <?php if ($edit_vehicle['image']): ?>
                <img src="<?= htmlspecialchars($edit_vehicle['image']) ?>" alt="Image actuelle" style="width: 100px;">
            <?php endif; ?>

            <button type="submit" name="update_vehicle">Mettre à jour</button>
        </form>

    <?php else: ?>
        <h2>Ajouter un véhicule</h2>

        <div class="top-bar">
            <form method="POST" enctype="multipart/form-data">
                <label>Marque :</label>
                <input type="text" name="marque" required>
                <label>Modèle :</label>
                <input type="text" name="modele" required>
                <label>Prix par jour :</label>
                <input type="number" name="prix_par_jour" required>
                <label>Disponible de :</label>
                <input type="date" name="disponible_de" required>
                <label>Disponible jusqu'à :</label>
                <input type="date" name="disponible_jusqua" required>
                <label>Image :</label>
                <input type="file" name="image" accept="image/*">
                <button type="submit" name="add_vehicle">Ajouter</button>
            </form>
        </div>

        <h2>Liste des véhicules</h2>
        <ul>
            <?php foreach ($vehicles as $vehicle): ?>
                <li class="vehicle-item">
            <span class="vehicle-info">
                <?= htmlspecialchars($vehicle['marque']) . " " . htmlspecialchars($vehicle['modele']) ?>
                - <?= htmlspecialchars($vehicle['prix_par_jour']) ?>€/jour
            </span>
                    <?php if ($vehicle['image']): ?>
                        <img class="vehicle-image" src="<?= htmlspecialchars($vehicle['image']) ?>" alt="Image de <?= htmlspecialchars($vehicle['modele']) ?>">
                    <?php endif; ?>
                    <form method="POST" class="edit-form">
                        <input type="hidden" name="vehicle_id" value="<?= $vehicle['id'] ?>">
                        <button type="submit" name="edit_vehicle" class="edit-button">Modifier</button>
                    </form>
                    <form method="POST" class="delete-form">
                        <input type="hidden" name="vehicle_id" value="<?= $vehicle['id'] ?>">
                        <button type="submit" name="delete_vehicle" class="delete-button">Supprimer</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>

        <div class="white-space"></div>

        <h2>Liste des réservations</h2>
        <ul>
            <?php foreach ($reservations as $reservation): ?>
                <li class="vehicle-item">
                <span class="vehicle-info">
                    Réservation de <?= htmlspecialchars($reservation['utilisateur']) ?>
                    pour le véhicule <?= htmlspecialchars($reservation['marque']) . " " . htmlspecialchars($reservation['modele']) ?>
                    - du <?= htmlspecialchars($reservation['date_reservation']) ?> au <?= htmlspecialchars($reservation['date_retour']) ?>
                </span>
                    <form method="POST" class="delete-form">
                        <input type="hidden" name="reservation_id" value="<?= $reservation['id'] ?>">
                        <button type="submit" name="delete_reservation" class="delete-button">Supprimer</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</body>
</html>
