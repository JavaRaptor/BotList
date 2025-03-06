<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
function isLoggedIn()
{
    return isset($_SESSION['user_id']);
}

function time_elapsed_string($datetime, $full = false)
{
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

$isAdmin = ($_SESSION['user_profile'] == 'Admin');

// Récupérer le nom d'utilisateur de la session
$username = $_SESSION['user_id'];

// Chemin du fichier JSON de l'utilisateur
$userFilePath = "users/{$username}.json";

// Vérifier si le fichier JSON de l'utilisateur existe
if (file_exists($userFilePath)) {
    // Lire les informations de l'utilisateur depuis le fichier JSON
    $userData = json_decode(file_get_contents($userFilePath), true);

    // Récupérer les informations du profil
    $fullName = $userData['profile'];
    // d'autres informations de profil peuvent être récupérées de la même manière
} else {
    // Rediriger vers une page d'erreur si le fichier n'existe pas
    header("Location: error.php");
    exit();
}

$userRequests = isset($userData['Bot']) ? $userData['Bot'] : [];

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil de
        <?php echo $username; ?>
    </title>
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

    <h2>Profil de
        <?php echo $username; ?>
    </h2>
    <?php if (!$isAdmin): ?>
        <h2>Bots</h2>

        <?php if ($userRequests): ?>
            <div class="card-container">
                <?php foreach ($userRequests as $demande): ?>
                    <?php
                    $addedTime = time_elapsed_string($demande['added_at']);
                    $fullDate = date('d/m/Y H:i:s', strtotime($demande['added_at']));
                    $totalVotes = $demande['nbrVotes'];
                    echo '<div class="card">
                            <div class="card-content">
                                <div class="image-container">
                                    <img src="' . $demande['image'] . '" alt="' . $demande['nom_bot'] . '">
                                </div>
                                <div class="text-container">
                                    <strong>' . $demande['nom_bot'] . '</strong><br>
                                    <p>' . $demande['description'] . '</p>
                                    <p>Catégorie: ' . $demande['categorie'] . '</p>
                                    <p class="added-time" data-full-date="' . $fullDate . '">Ajouté il y a ' . $addedTime . '</p>
                                    <div class="vote-invite-buttons">
                                        <form action="vote.php" method="post">
                                            <input type="hidden" name="bot_id" value="' . $demande['nom_bot'] . '">
                                            <button type="submit">Voter (' . $totalVotes . ')</button>
                                        </form>
                                    <a href="' . $demande['inviteLink'] . '" target="_blank"><button class="btn">Inviter</button></a>
                                    </div>
                                </div>
                            </div>';
                    echo '</div>';
                    ?>
                    <hr>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Aucune bots enregistrer</p>
            <?php endif; ?>
        <?php else: ?>
            <p>t admin fdp</p>
        <?php endif; ?>
        <button onclick="redirectToPage('logout.php')">Déconnexion</button>

        <script>
            function redirectToPage(page) {
                window.location.href = page;
            }
        </script>

</body>

</html>