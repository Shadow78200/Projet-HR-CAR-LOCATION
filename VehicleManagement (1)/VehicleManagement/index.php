<?php
session_start();
require 'db.php';

if (isset($_SESSION['user_id'])) {
    header('Location: vehicles.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['login'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['mot_de_passe'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            header('Location: vehicles.php');
        } else {
            $error = "Identifiants invalides.";
        }
    } elseif (isset($_POST['register'])) {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("INSERT INTO utilisateurs (nom, email, mot_de_passe) VALUES (?, ?, ?)");
        $stmt->execute([$name, $email, $password]);

        header('Location: index.php');
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <?php
        require_once('head.php');
    ?>
</head>
<body>

    <?php
        require_once('header.php');
    ?>
    <div class="page-content">
        <div class="container-fluid bg-image">
            <div class="row justify-content-center align-items-center vh-100">
                <div class="col-md-4">
                    <form method="POST" class="p-4 bg-light rounded shadow">
                        <h2 style="color:black">Connexion</h2>
                        <div class="form-group">
                            <label for="login-email">Email :</label>
                            <input type="email" class="form-control" id="login-email" name="email" placeholder="Entrez votre email">
                        </div>
                        <div class="form-group">
                            <label for="login-password">Mot de passe :</label>
                            <input type="password" name="password" class="form-control" id="login-password" placeholder="Entrez votre mot de passe">
                        </div>
                        <button type="submit" class="btn btn-success btn-block" name="login">Se connecter</button>
                    </form>
                </div>
                <div class="col-md-4">
                    <form method="POST" class="p-4 bg-light rounded shadow">
                        <h2 style="color:black">Inscription</h2>

                        <div class="form-group">
                            <label for="register-name">Nom :</label>
                            <input type="text" class="form-control" name="name" placeholder="Nom" required>
                        </div>
                        <div class="form-group">
                            <label for="register-email">Email :</label>
                            <input type="email" class="form-control" name="email" placeholder="Email" required>
                        </div>
                        <div class="form-group">
                            <label for="register-password">Mot de passe :</label>
                            <input type="password" class="form-control" name="password" placeholder="Mot de passe" required>
                        </div>
                        <button type="submit" class="btn btn-success btn-block" name="register">S'inscrire</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php if (isset($error)) echo "<p>$error</p>"; ?>
    
    <?php
        require_once('footer.php');
    ?>
</body>
</html>