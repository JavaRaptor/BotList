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
    <title>Ajouter un Bot - Liste des Bots Discord</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <header>
        <div class="navbar">
            <h1>Liste des Bots Discord</h1>
            <nav>
                <ul class="nav-list">
                    <li><a href="index.php">Accueil</a></li>
                    <li><a href="#">Top Bots</a></li>
                    <li><a href="#">Ajouter un Bot</a></li>
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
        <div class="add-bot-form">
            <h2>Ajouter un Bot</h2>
            <form action="process_add_bot.php" method="post" enctype="multipart/form-data">
                <label for="nom_bot">Nom du Bot:</label>
                <input type="text" name="nom_bot" required>

                <label for="description">Description:</label>
                <textarea name="description" required></textarea>

                <label for="categorie">Catégorie:</label>
                <select name="categorie" required>
                    <option value="musique">Musique</option>
                    <option value="Multifonctions">Multifonctions</option>
                    <option value="jeux">Jeux</option>
                    <option value="divertissement">Divertissement</option>
                    <!-- Ajoutez d'autres options au besoin -->
                </select>

                <label for="image">Image du Bot:</label>
                <input type="file" name="image" accept="image/*">

                <label for="bot_id">Id du Bot:</label>
                <input type="text" name="bot_id" required>

                <button type="submit">Ajouter</button>
            </form>
        </div>
    </section>

    <!-- Ajout du script JavaScript pour gérer le menu déroulant -->
    <script src="script.js"></script>
</body>

</html>