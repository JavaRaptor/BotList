<?php
session_start();

// Vérifiez si l'utilisateur est connecté
if (isset($_SESSION['user_id'])) {
    // Détruire toutes les données de session
    session_unset();
    session_destroy();

    // Rediriger vers la page d'accueil ou une autre page après la déconnexion
    header("Location: index.php");
    exit();
} else {
    // Si l'utilisateur n'est pas connecté, redirigez-le vers la page d'accueil
    header("Location: index.php");
    exit();
}
?>
