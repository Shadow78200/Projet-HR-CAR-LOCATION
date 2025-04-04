<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $message = htmlspecialchars($_POST['message']);

    // Simule l'envoi de message (à compléter avec une fonction d'envoi d'e-mail si nécessaire)
    $success = "Votre message a été envoyé avec succès. Nous vous répondrons dans les plus brefs délais.";
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

    <div class="container mt-5 contact-section">
        <h1 class="text-center mb-4" style="margin-top:110px">Nous contacter</h1>
        <?php if (isset($success)): ?>
            <div class="alert alert-success">
                <?= $success; ?>
            </div>
        <?php endif; ?>
        <form method="POST" action="contact.php">
            <div class="form-group" >
                <label for="name" style="color: white;">Nom :</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Votre nom complet" required>
            </div>
            <div class="form-group">
                <label for="email" style="color: white;">Email :</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Votre adresse email" required>
            </div>
            <div class="form-group">
                <label for="message" style="color: white;">Message :</label>
                <textarea class="form-control" id="message" name="message" rows="5" placeholder="Votre message" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Envoyer</button>
        </form>
    </div>

    <?php
        require_once('footer.php');
    ?>
</body>
</html>
