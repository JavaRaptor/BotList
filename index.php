<?php
session_start();

$identity = null;

function isLoggedIn()
{
    return isset($_SESSION['user_id']);
}

function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff_str = [
        'y' => 'année',
        'm' => 'moi',
        'd' => 'jour',
        'h' => 'heure',
        'i' => 'minute',
        's' => 'seconde',
    ];

    foreach ($diff_str as $key => &$value) {
        if ($diff->$key) {
            $value = $diff->$key . ' ' . $value . ($diff->$key > 1 ? 's' : '');
            if ($key === 'm' && $diff->$key === 1) {
                // Pour le mois, si la différence est exactement 1, ajustez le texte
                $value = '1 mois';
            }
        } else {
            unset($diff_str[$key]);
        }
    }

    if (!$full) {
        $diff_str = array_slice($diff_str, 0, 1);
    }

    return $diff_str ? implode(', ', $diff_str) . ' ago' : 'maintenant';
}



?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Bots Discord</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header>
        <div class="navbar">
            <h1>Liste des Bots Discord</h1>
            <form class="search">
                <input type="text" name="search" id="search" placeholder="Nom du Bot">
                <button type="submit">Rechercher</button>
            </form>
            <nav>
                <ul class="nav-list">
                    <li><a href="#">Accueil</a></li>
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
        <form action="index.php" method="get" class="search">
            <label for="categorie">Rechercher par catégorie:</label>
            <select name="category">
                <!-- Liste déroulante des catégories existantes -->
                <option value="Toutes">Toutes</option>
                <option value="Multifonctions">Multifonctions</option>
                <option value="Musique">Musique</option>
                <option value="Jeux">Jeux</option>
                <!-- Ajoutez d'autres catégories au besoin -->
            </select>
            <button type="submit">Rechercher</button>
        </form>
    </header>

    <section>
        <?php
        // Charger le contenu du fichier JSON (remplacez le chemin par le vôtre)
        $jsonData = file_get_contents('./bot.json');

        // Décoder le JSON en tableau associatif
        $botList = json_decode($jsonData, true);

        if (isset($_GET['category']) && $_GET['category'] !== 'Toutes') {
            $categoryFilter = $_GET['category'];

            // Filtrer les bots par catégorie
            $botList = array_filter($botList, function ($bot) use ($categoryFilter) {
                return $bot['categorie'] === $categoryFilter;
            });
        }

        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $searchTerm = htmlspecialchars($_GET['search']);

            // Filtrer les bots par nom
            $botList = array_filter($botList, function ($bot) use ($searchTerm) {
                return stripos($bot['nom_bot'], $searchTerm) !== false;
            });
        }

        // Tri du tableau des bots par le nombre total de votes (du plus au moins)
        usort($botList, function ($a, $b) {
            return $b['nbrVotes'] - $a['nbrVotes'];
        });

        // Afficher la liste des bots triés par le nombre total de votes
        echo '<div class="card-container">';
        $count = 0;
        foreach ($botList as $bot) {
            $addedTime = time_elapsed_string($bot['added_at']);
            $fullDate = date('d/m/Y H:i:s', strtotime($bot['added_at']));
            $totalVotes = $bot['nbrVotes'];
            echo '<div class="card">
            <div class="card-content">
                <div class="image-container">
                    <img src="' . $bot['image'] . '" alt="' . $bot['nom_bot'] . '">
                </div>
                <div class="text-container">
                    <strong>' . $bot['nom_bot'] . '</strong><br>
                    <p>' . $bot['description'] . '</p>
                    <p>Catégorie: ' . $bot['categorie'] . '</p>
                    <p class="added-time" data-full-date="' . $fullDate . '">Ajouté il y a ' . $addedTime . '</p>
                    <div class="vote-invite-buttons">
                        <form action="vote.php" method="post">
                            <input type="hidden" name="bot_id" value="' . $bot['nom_bot'] . '">
                            <button type="submit">Voter (' . $totalVotes . ')</button>
                        </form>
                        <a href="' . $bot['inviteLink'] . '" target="_blank"><button class="btn">Inviter</button></a>
                    </div>
                </div>
            </div>
          </div>';
        }
        echo '</div>';
        ?>
    </section>

</body>

</html>