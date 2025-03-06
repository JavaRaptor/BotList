<?php

session_start();

function isLoggedIn()
{
    return isset($_SESSION['user_id']);
}

$username = $_SESSION['user_id'];

// Chemin du fichier JSON de l'utilisateur
$userFilePath = "users/{$username}.json";

function updateBotVotes(&$bot, $username)
{
    // Vérifier si la clé 'notes' est définie dans le tableau $bot
    if (isset($bot['notes']) && is_array($bot['notes'])) {
        if (!userAlreadyVoted($bot, $username)) {
            $bot['nbrVotes']++;
            $bot['notes'][] = [
                "id" => $username
            ];
        }
    } else {
        // Si la clé 'notes' n'est pas définie, vous pouvez gérer cela selon vos besoins
        // Par exemple, initialiser 'notes' à un tableau vide
        $bot['notes'] = [];
        $bot['nbrVotes']++; // Incrémenter le nombre de votes
        $bot['notes'][] = [
            "id" => $username
        ];
    }
}

function userAlreadyVoted($bot, $username)
{
    foreach ($bot['notes'] as $note) {
        if ($note['id'] === $username) {
            return true;
        }
    }
    return false;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["bot_id"])) {
    $botId = htmlspecialchars($_POST["bot_id"]);

    // Charger le contenu du fichier JSON
    $jsonData = file_get_contents('./bot.json');
    $botList = json_decode($jsonData, true);

    $jsonUser = file_get_contents("users/{$username}.json");
    $botUser = json_decode($jsonUser, true);

    $foundBot = false;

    foreach ($botList as &$bot) {
        if ($bot['nom_bot'] === $botId) {
            if (userAlreadyVoted($bot, $username)) {
                // Rediriger vers la page déjà voté
                header("Location: alreadyVoted.php");
                exit();
            }

            // Mettre à jour les votes
            updateBotVotes($bot, $username);
            $foundBot = true;
            break;
        }
    }

    foreach ($botUser['Bot'] as &$bot) {
        if ($bot['nom_bot'] === $botId) {
            if (userAlreadyVoted($bot, $username)) {
                // Rediriger vers la page déjà voté
                header("Location: alreadyVoted.php");
                exit();
            }

            // Mettre à jour les votes
            updateBotVotes($bot, $username);
            $foundBot = true;
            break;
        }
    }

    // Si le bot n'est pas trouvé, vous pouvez gérer cela selon vos besoins
    if (!$foundBot) {
        header("Location: vote_error.php");
        exit();
    }

    // Réencoder le tableau en JSON
    $updatedJsonData = json_encode($botList, JSON_PRETTY_PRINT);
    $updatedJsonUser = json_encode($botUser, JSON_PRETTY_PRINT);

    // Réécrire le fichier JSON avec les nouvelles données
    file_put_contents('./bot.json', $updatedJsonData);
    file_put_contents("users/{$username}.json", $updatedJsonUser);

    // Rediriger avec un message de succès
    header("Location: index.php");
    exit();
} else {
    // Rediriger vers une page d'erreur si la requête n'est pas de type POST
    header("Location: error.php");
    exit();
}