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
    <title>Erreur de lien Discord</title>
    <link rel="stylesheet" href="style.css"> <!-- Assurez-vous d'ajuster le lien vers votre feuille de style CSS -->
</head>

<body>
    <header>
    <div class="navbar">
        <h1>Erreur de lien Discord</h1>
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

    <section>
        <p>Le lien d'invitation Discord que vous avez fourni n'est pas valide.</p>
    </section>

    <button onclick="goBack()">Retour</button>

    <script>
        function goBack() {
            window.history.back();
        }
    </script>

    <!-- Pied de page ou autres sections si nécessaire -->

</body>

</html>