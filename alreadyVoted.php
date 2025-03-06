<?php
session_start();

$identity = null;

function isLoggedIn()
{
    return isset($_SESSION['user_id']);
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Déjà Voté</title>
    <link rel="stylesheet" href="style.css"> <!-- Inclure le fichier style.css si nécessaire -->
</head>

<body>
    <header>
    <div class="navbar">
        <h1>Déjà Voté</h1>
            <nav>
                <ul class="nav-list">
                    <li><a href="index.php">Accueil</a></li>
                    <li><a href="#">Top Bots</a></li>
                    <li><a href="add_bot.php">Ajouter un Bot</a></li>
                    <!-- Nouvel élément pour le bouton Connexion -->
                    <?php
                    if (isLoggedIn()) {
                        echo '<a href="profil.php" class="right">Profil</a>';
                    } else {
                        echo '<a href="login.php" class="right">Connexion</a>';
                    }
                    ?>
                </ul>
            </nav>
        </div>
        
    </header>

    <section><p>Vous avez déjà voté pour ce bot.</p></section>

    <button onclick="goBack()">Retour</button>

    <script>
        function goBack() {
            window.history.back();
        }
    </script>

</body>

</html>
