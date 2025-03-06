<?php
session_start();

function isLoggedIn()
{
    return isset($_SESSION['user_id']);
}

function isDiscordInviteValid($inviteLink, $bot_id)
{
    $apiUrl = 'https://discord.com/api/oauth2/authorize?client_id=' . $bot_id . '&permissions=0&scope=bot';
    
    // Effectuer une requête GET à l'API Discord
    $response = file_get_contents($apiUrl);

    // Décoder la réponse JSON
    $data = json_decode($response, true);

    // Vérifier si la réponse contient une erreur ou si le serveur est indisponible
    if ($data === null || isset($data['code'])) {
        return false;
    }

    // Vérifier d'autres conditions si nécessaire (par exemple, le bot est dans le serveur, etc.)
    // ...

    return true;
}

$username = $_SESSION['user_id'];

// Chemin du fichier JSON de l'utilisateur
$userFilePath = "users/{$username}.json";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $nom_bot = htmlspecialchars($_POST["nom_bot"]);
    $description = htmlspecialchars($_POST["description"]);
    $image = htmlspecialchars($_POST["image"]);
    $bot_id = htmlspecialchars($_POST["bot_id"]);
    $categorie = htmlspecialchars($_POST["categorie"]);

    $uploadDir = 'uploads/';

    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Vérifier si un fichier a été téléchargé
    if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
        // Générer un nom de fichier unique
        $fileName = $nom_bot . '.' . pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);

        // Chemin complet du fichier
        $imagePath = $uploadDir . $fileName;

        // Déplacer le fichier téléchargé vers le dossier de téléchargement avec le nouveau nom
        move_uploaded_file($_FILES['image']['tmp_name'], $imagePath);
    } else {
        // Gérer l'erreur du téléchargement si nécessaire
    }
    // Charger le contenu du fichier JSON
    $jsonData = file_get_contents('./bot.json');

    // Décoder le JSON en tableau associatif
    $botList = json_decode($jsonData, true);

    $link = 'https://discord.com/api/oauth2/authorize?client_id=' . $bot_id . '&permissions=0&scope=bot';

    // Ajouter le nouveau bot aux données existantes
    $newBot = [
        'user_id' => $username,
        'bot_id' => $bot_id,
        "nom_bot" => $nom_bot,
        "description" => $description,
        "added_at" => date('Y-m-d H:i:s'),
        "nbrVotes" => 0, // Initialiser le nombre de votes à 0
        "notes" => [], // Initialiser les notes à un tableau vide
        "image" => $imagePath,
        "inviteLink" => $link,
        "categorie" => $categorie
    ];

    $botList[] = $newBot;

    // Réencoder le tableau en JSON
    file_put_contents('./bot.json', json_encode($botList, JSON_PRETTY_PRINT));

    $botUser = json_decode(file_get_contents($userFilePath), true);

    $botUser['Bot'][] = [
        'user_id' => $username,
        'bot_id' => $bot_id,
        "nom_bot" => $nom_bot,
        "description" => $description,
        "added_at" => date('Y-m-d H:i:s'),
        "nbrVotes" => 0, // Initialiser le nombre de votes à 0
        "image" => $imagePath,
        "inviteLink" => $link,
        "categorie" => $categorie
    ];

    // Écrire les données mises à jour dans le fichier JSON des demandes en attente
    file_put_contents($userFilePath, json_encode($botUser, JSON_PRETTY_PRINT));

    // Rediriger vers la page d'accueil ou une autre page appropriée
    header("Location: index.php");
    exit();
} else {
    // Rediriger vers une page d'erreur si la requête n'est pas de type POST
    header("Location: error.php");
    exit();
}
?>