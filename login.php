<?php
session_start();

// Fonction pour vérifier les informations de connexion d'un utilisateur
function authenticateUser($username, $password)
{
    $userFilePath = "users/{$username}.json";

    // Vérifiez si le fichier JSON de l'utilisateur existe
    if (file_exists($userFilePath)) {
        // Lire les informations de l'utilisateur depuis le fichier JSON
        $userData = json_decode(file_get_contents($userFilePath), true);

        // Vérifiez si le mot de passe correspond
        if (password_verify($password, $userData['password'])) {
            // Stockez le profil de l'utilisateur dans la session
            $_SESSION['user_profile'] = $userData['profile'];
            $_SESSION['user_mail'] = $userData['mail'];
            return true;
        }
    }

    return false;
}
function isLoggedIn()
{
    return isset($_SESSION['user_id']);
}

// Vérifiez si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Authentification de l'utilisateur
    if (authenticateUser($username, $password)) {
        // Enregistrez l'ID de l'utilisateur dans la session
        $_SESSION['user_id'] = $username;

        // Rediriger vers la page protégée ou la page d'accueil
        header("Location: index.php");
        exit();
    } else {
        $error = "Nom d'utilisateur ou mot de passe incorrect.";
    }
}
?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Votre Site</title>
    <link rel="stylesheet" href="style.css"> <!-- Inclure le fichier style.css -->
    <link rel="stylesheet" href="profil.css"> <!-- Inclure le fichier style.css -->
</head>

<body>

    <header>
        <div class="navbar">
            <h1>Liste des Bots Discord</h1>
            <nav>
                <ul class="nav-list">
                    <li><a href="#">Accueil</a></li>
                    <li><a href="#">Top Bots</a></li>
                    <li><a href="add_bot.php">Ajouter un Bot</a></li>
                    <!-- Nouvel élément pour le bouton Connexion -->
                    <li><a href="login.php">Connexion</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="container">
        <form method="post" action="">
            <h2>Connexion</h2>

            <?php if (isset($error)): ?>
                <p class="error">
                    <?php echo $error; ?>
                </p>
            <?php endif; ?>

            <label for="username">Nom d'utilisateur :</label>
            <input type="text" name="username" required>

            <label for="password">Mot de passe :</label>
            <input type="password" name="password" required>

            <button type="submit" name="login">Se connecter</button>
        </form>
        <div class="register">
            <p>Vous n'avez pas de compte ? <a href="register.php">S'enregistrer</a></p>
        </div>
    </div>

</body>

</html>