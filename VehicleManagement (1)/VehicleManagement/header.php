<header>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="index.php">HR CAR LOCATION</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <form method="POST" style="display: inline;">
                        <a class="nav-link" href="about.php">À propos</a>
                    </form>
                </li>
                <li class="nav-item">
                    <form method="POST" style="display: inline;">
                        <a class="nav-link" href="contact.php">Contact</a>
                    </form>
                </li>
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'utilisateur'): ?>
                    <li class="nav-item">
                        <form method="POST" style="display: inline;">
                            <a class="nav-link" href="history.php">Historique de réservation</a>
                        </form>
                    </li>
                    <li class="nav-item">
                        <form method="POST" style="display: inline;">
                            <button type="submit" name="logout" class="nav-link btn btn-danger text-white">Déconnexion</button>
                        </form>
                    </li>
                <?php endif; ?>
                
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                    <li class="nav-item">
                        <form method="POST" style="display: inline;">
                            <a href="admin.php" class="nav-link">Panneau administration</a>
                        </form>
                    </li>
                    <li class="nav-item">
                        <form method="POST" style="display: inline;">
                            <button type="submit" name="logout" class="nav-link btn btn-danger text-white">Déconnexion</button>
                        </form>                    
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>
</header>
