<?php
session_start();

// Fonction pour créer un nouvel utilisateur
function createUser($username, $password, $mail)
{
    $userFilePath = "users/{$username}.json";

    // Vérifiez si le fichier JSON de l'utilisateur n'existe pas déjà
    if (!file_exists($userFilePath)) {
        // Hash du mot de passe
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Création du tableau d'informations utilisateur
        $userData = [
            'username' => $username,
            'mail' => $mail,
            'password' => $hashedPassword,
            'profile' => 'client',
            'premium' => 'non'
        ];

        // Encodage en JSON et enregistrement dans le fichier
        file_put_contents($userFilePath, json_encode($userData));

        // Redirection vers la page d'accueil après l'inscription réussie
        header("Location: index.php");
        exit();
    } else {
        // Utilisateur déjà enregistré avec ce nom
        return false;
    }
}

// Vérifiez si le formulaire d'inscription est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $mail = $_POST['mail'];

    // Création de l'utilisateur
    $userCreated = createUser($username, $password, $mail);

    if (!$userCreated) {
        $error = "Nom d'utilisateur déjà pris. Veuillez choisir un autre nom.";
    }
    header("Location: index.php");
}