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

// Initialisation des variables de filtre
$price = isset($_POST['price']) ? $_POST['price'] : null;
$date = isset($_POST['date']) ? $_POST['date'] : null;

// Requête SQL pour récupérer les véhicules avec ou sans filtre
if ($_SERVER['REQUEST_METHOD'] == 'POST' && ($price || $date)) {
    // Préparation de la requête avec des filtres dynamiques
    $query = "SELECT * FROM vehicules WHERE 1=1";
    $params = [];

    // Filtre sur le prix maximum (optionnel)
    if ($price) {
        $query .= " AND prix_par_jour <= ?";
        $params[] = $price;
    }

    // Filtre sur les dates de disponibilité (optionnel)
    if ($date) {
        $query .= " AND disponible_de <= ? AND disponible_jusqua >= ?";
        $params[] = $date; // Vérifie que la date est après ou égale à `disponible_de`
        $params[] = $date; // Vérifie que la date est avant ou égale à `disponible_jusqua`
    }

    // Exécution de la requête avec les paramètres
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $vehicles = $stmt->fetchAll();
} else {
    // Si aucun filtre n'est appliqué, récupérez tous les véhicules
    $stmt = $pdo->query("SELECT * FROM vehicules");
    $vehicles = $stmt->fetchAll();
}
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

    <!-- Contenu principal -->
    <div class="container my-5">
        <h1 class="text-center mb-4" style="margin-top:110px">Liste des véhicules</h1>

        <!-- Formulaire de filtres -->
        <form class="row mb-4" method="POST" action="vehicles.php">
            <div class="col-md-4">
                <input type="number" class="form-control" id="price" name="price" placeholder="Prix maximum (€)">
            </div>
            <div class="col-md-4">
                <input type="date" class="form-control" id="date" name="date">
                <small class="form-text text-muted">Date de début</small>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary w-100 mt-md-0 mt-2">Filtrer</button>
            </div>
        </form>


        <!-- Liste des véhicules -->
        <div class="row">
            <?php if (count($vehicles) > 0): ?>
                <?php if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin'): ?>
                    <?php foreach ($vehicles as $vehicle) {
                        echo '
                        <div class="col-md-4 mb-4">
                            <div class="card">
                                <img src="' . $vehicle["image"] . '" class="card-img-top" alt="' . $vehicle["name"] . '">
                                <div class="card-body">
                                    <h5 class="card-title">' . $vehicle["marque"] . ' ' . $vehicle["modele"] . '</h5>
                                    <p class="card-text">Prix : ' . $vehicle["prix_par_jour"] . ' €/jour</p>
                                    <a href="reserve.php?id=' . urlencode($vehicle["id"]) . '" class="btn btn-success">Réserver</a>
                                </div>
                            </div>
                        </div>
                        ';
                    }
                    ?>
                <?php else: ?>   
                    <?php foreach ($vehicles as $vehicle) {
                        echo '
                        <div class="col-md-4 mb-4">
                            <div class="card">
                                <img src="' . $vehicle["image"] . '" class="card-img-top" alt="' . $vehicle["name"] . '">
                                <div class="card-body">
                                    <h5 class="card-title">' . $vehicle["marque"] . ' ' . $vehicle["modele"] . '</h5>
                                    <p class="card-text">Prix : ' . $vehicle["prix_par_jour"] . ' €/jour</p>
                                </div>
                            </div>
                        </div>
                        ';
                    }
                    ?>
                <?php endif; ?>         
            <?php else: ?>
                <p>Aucun véhicule ne correspond aux critères de recherche.</p>
            <?php endif; ?>
        </div>
    </div>

    <?php
        require_once('footer.php');
    ?>

    <!-- Scripts Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.4.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
